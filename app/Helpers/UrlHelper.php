<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class UrlHelper
{
    /**
     * Generate an absolute URL for emails
     * 
     * This function ensures that URLs in emails always use the correct domain,
     * even when the application is running in a local environment.
     * 
     * @param string $route The route name
     * @param array $parameters The route parameters
     * @return string The absolute URL
     */
    public static function emailUrl(string $route, $parameters = [])
    {
        // Get the current APP_URL from config
        $appUrl = Config::get('app.url');
        
        // Generate the route URL
        $url = route($route, $parameters, false); // false = relative URL
        
        // Create the absolute URL
        return $appUrl . $url;
    }
    
    /**
     * Generate a fallback URL for the customer dashboard
     * 
     * This function provides a fallback URL in case the specific appointment URL doesn't work
     * 
     * @return string The absolute URL to the customer dashboard
     */
    public static function customerDashboardUrl()
    {
        // Get the current APP_URL from config
        $appUrl = Config::get('app.url');
        
        // Generate the route URL
        $url = route('customer.dashboard', [], false); // false = relative URL
        
        // Create the absolute URL
        return $appUrl . $url;
    }
}
