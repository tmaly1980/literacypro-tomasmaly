<?php
namespace App\Libraries;

use Collective\Html\HtmlBuilder as IlluminateHtmlBuilder;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class HtmlBuilder extends IlluminateHtmlBuilder {
    function yieldSection($section)
    {
        return View::yieldContent($section);
    }
    function loadPartial($viewFile,$vars=[])
    {
        return View::make($viewFile)->with($vars)->render();
    }
    function controllerAction()
    {
        # get current controller name and action if needed.
        $currentRoute = Route::getCurrentRoute();
        #error_log("CURROT=".print_r($currentRoute,true));
        $path = $currentRoute->getActionName();
        $path_parts = explode("\\", $path);
        list($controllerClass,$action) = explode("@", array_pop($path_parts));
        $controller = snake_case(preg_replace('/Controller$/', '', $controllerClass));
        return [$controller,$action];
        #$view->with("currentAction", $action);
        #$view->with("currentController", $controller);
    }
    function currentAction()
    {
        #return "BAR";
        list($controller,$action) = $this->controllerAction();
        return $action;
    }    
    function currentController()
    {
        #return "FOO";
        list($controller,$action) = $this->controllerAction();
        return $controller;
    }
    function thing()
    {
        return $this->humanize(str_singular($this->currentController));
    }
    function ucThing()
    {
        return title_case($this->thing());
    }

    function humanize($className) # ReceivingDetail => receiving detail
    {
        return str_replace("_", " ", snake_case($className));
    }

    function addlink($label = 'Add', $url=null, $options=[])
    {
        if(empty($url)) { $url = ['action'=>'create']; }

        return $this->gblink('plus',$label,$url,$options, 'success');
    }
    function editlink($label = 'Edit', $url=null, $options=[])
    {
        if(empty($url)) { $url = ['action'=>'edit']; }
        return $this->gblink('edit',$label,$url,$options, 'warning');
    }

    # MUST be outside of forms, since uses own
    function deletelink($label = 'Delete', $url=null, $options=[]) 
    {
        if(empty($url)) { $url = ['action'=>'destroy']; } # Usually needs model=>$id, too
        $actionParams = $this->urlToAction($url); # ['UsersController@destroy',1234]
        #error_log("ACT_PARAMS=".print_r($actionParams,true));
        if(!isset($options['class'])) 
        {
            $options['class'] = 'btn btn-danger';
        }
        $options['type'] = 'submit';

        return 
            Form::open(['method'=>'DELETE','action'=>$actionParams,'class'=>'inline-block'])
            . $this->button($this->g("trash")." $label",$options)
            . Form::close();
            # XXX add confirm...
    }
    function backlink($label = 'Back', $url=null, $options=[])
    {
        if(empty($url)) { $url = ['action'=>'index']; }
        return $this->gblink('chevron-left',$label,$url,$options, 'default');
    }

    #######################################
    function span($class)
    {
        return "<span class='$class'></span>";
    }
    function divclear()
    {
        return "<div class='clear'></div>";
    }
    function icon($glyph)
    {
        if(preg_match("/fa-(.+)$/",$glyph,$matches))
        {
            return $this->fa($matches[1]);
        } else {
            return $this->g($glyph);
        }
    }
    
    function g($glyph)
    {
        return "<span class='glyphicon glyphicon-$glyph'></span>";
    }    
    
    function fa($glyph) 
    {
        return "<i class='fa fa-$glyph'></i>";
    }

    function gblink($glyph,$label,$url,$options=[],$type='default')
    {
        $label = $this->icon($glyph)." $label";
        return $this->blink($label,$url,$options,$type);
    }

    function glink($glyph,$label,$url,$options=[])
    {
        $label = $this->icon($glyph)." $label";
        return $this->link($url,$label,$options);
    }

    function blink($label,$url,$options=[],$type='default')
    {
        if(is_string($options)) { $options = ['class'=>$options]; }
        if(empty($options['class'])) { $options['class'] = "btn btn-$type"; }

        return $this->link($url,$label,$options);
    }

    function urlToAction(array $url=[],$stringonly=false) # ['action'=>'foo'] => "MyController@foo" or ["MyC@foo",1234]
    {
        $controller = !empty($url['controller']) ? $url['controller'] : $this->currentController();
        $controllerClass = studly_case($controller)."Controller";
        $method = !empty($url['action']) ? $url['action'] : 'index';
        unset($url['controller']); unset($url['action']);
        $action = "$controllerClass@$method";
        if(empty($url) || $stringonly) { return $action; } # Just a string

        return array_merge([$action], $url); # Named params
    }

    function urlToRoute(array $url=[],$stringonly=false) # ['action'=>'foo'] => controller.foo or ["controller.foo",1234]
    { # Rest-appropriate
        $controller = !empty($url['controller']) ? $url['controller'] : $this->currentController();
        $controllerClass = studly_case($controller)."Controller";
        $method = !empty($url['action']) ? $url['action'] : 'index';
        unset($url['controller']); unset($url['action']);
        $route = "$controller.$method";
        if(empty($url) || $stringonly) { return $route; } # Just a string

        return array_merge([$route], $url); # Named params
    }

    # Support [ controller => foo, action => bar ] syntax => FooController@bar
    # takes extra 'parameters' option for named parameters...
    function linkAction($url,  $title = null, $parameters = [], $attributes = [], $escape=false)
    {
        $action = $url;
        if(is_array($url))
        {  
            $action = $this->urlToAction($url,true); # Only get string portion

            # Pass other parameters to params, so it can interpolate the url.
            unset($url['controller']);
            unset($url['action']);

            $parameters = array_merge($parameters,$url); # pass user=>23, etc
        }

        // if(!preg_match("/\\/", $url)) # Relative
        // {
        //     $this->setRootControllerNamespace("App\\Http");
        // }

        return parent::linkAction($action,$title,$parameters,$attributes,$escape);
    }

    # DEFAULT TO not escaping html
    function link($url, $title=null,$attributes=[], $secure=null,$escape=false) 
    {
        if(!empty($attributes['confirm']))
        {
            $attributes['onclick'] = "return confirm('".addslashes($attributes['confirm'])."');";
            unset($attributes['confirm']);
        }
        # if attributes is a string, treat as 'class'
        if(!empty($attributes) && is_string($attributes))
        {
            $attributes = ["class"=>$attributes];
        }
        #error_log("URL=".print_r($url,true));

        if(is_array($url) || preg_match("/^(?!mailto:).+@/",$url)) # fix UserController@index or [action=>index]
        {
            return $this->linkAction($url,$title,[],$attributes,$secure,$escape);
        } else {
            return parent::link($url,$title,$attributes,$secure,$escape);
        }
    }

    function alink($title=null,$url=null,$attributes=[], $secure=null,$escape=false) # reversed
    {
        return $this->link($url, $title,$attributes, $secure,$escape);
   
    }

    function button($value=null,$attributes=[]) # Adds confirm
    {
        if(!empty($attributes['confirm']))
        {
            $attributes['onclick'] = "return confirm('".addslashes($attributes['confirm'])."')";
            unset($attributes['confirm']);
        }

        error_log("ATTRS=".print_r($attributes,true));

        return Form::button($value,$attributes);
    }


}