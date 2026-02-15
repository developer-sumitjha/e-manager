<?php

namespace App\Helpers;

class StorefrontHelper
{
    /**
     * Check if current request is via subdomain
     */
    public static function isSubdomainAccess()
    {
        $hostname = request()->getHost();
        $parts = explode('.', $hostname);
        
        // Check if it's a localhost subdomain (e.g., primax.localhost)
        if (count($parts) === 2 && strtolower(end($parts)) === 'localhost') {
            return true;
        }
        
        // Check if it's a production subdomain (e.g., primax.example.com)
        if (count($parts) >= 3) {
            $firstPart = strtolower($parts[0]);
            // Skip special subdomains
            if (!in_array($firstPart, ['www', 'super', 'admin'])) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get subdomain from request
     */
    public static function getSubdomain()
    {
        // Try to get from route parameter first
        $subdomain = request()->route('subdomain');
        if ($subdomain) {
            return $subdomain;
        }
        
        // Try to get from hostname
        $hostname = request()->getHost();
        $parts = explode('.', $hostname);
        
        if (count($parts) === 2 && strtolower(end($parts)) === 'localhost') {
            return $parts[0];
        }
        
        if (count($parts) >= 3) {
            $firstPart = strtolower($parts[0]);
            if (!in_array($firstPart, ['www', 'super', 'admin'])) {
                return $parts[0];
            }
        }
        
        return null;
    }
    
    /**
     * Generate storefront route URL
     * Automatically uses subdomain routes if accessed via subdomain, otherwise uses path-based routes
     */
    public static function route($name, $params = [])
    {
        $isSubdomain = self::isSubdomainAccess();
        $currentSubdomain = self::getSubdomain();
        
        // Map route names
        $routeMap = [
            'storefront.preview' => $isSubdomain ? 'storefront.subdomain.preview' : 'storefront.preview',
            'storefront.product' => $isSubdomain ? 'storefront.subdomain.product' : 'storefront.product',
            'storefront.category' => $isSubdomain ? 'storefront.subdomain.category' : 'storefront.category',
            'storefront.dynamic' => $isSubdomain ? 'storefront.subdomain.dynamic' : 'storefront.dynamic',
            'storefront.cart' => $isSubdomain ? 'storefront.subdomain.cart' : 'storefront.cart',
            'storefront.cart.add' => $isSubdomain ? 'storefront.subdomain.cart.add' : 'storefront.cart.add',
            'storefront.cart.update' => $isSubdomain ? 'storefront.subdomain.cart.update' : 'storefront.cart.update',
            'storefront.cart.remove' => $isSubdomain ? 'storefront.subdomain.cart.remove' : 'storefront.cart.remove',
            'storefront.cart.clear' => $isSubdomain ? 'storefront.subdomain.cart.clear' : 'storefront.cart.clear',
            'storefront.checkout' => $isSubdomain ? 'storefront.subdomain.checkout' : 'storefront.checkout',
            'storefront.checkout.process' => $isSubdomain ? 'storefront.subdomain.checkout.process' : 'storefront.checkout.process',
            'storefront.checkout.success' => $isSubdomain ? 'storefront.subdomain.checkout.success' : 'storefront.checkout.success',
            'storefront.coupon.apply' => $isSubdomain ? 'storefront.subdomain.coupon.apply' : 'storefront.coupon.apply',
            'storefront.coupon.remove' => $isSubdomain ? 'storefront.subdomain.coupon.remove' : 'storefront.coupon.remove',
            'storefront.contact.submit' => $isSubdomain ? 'storefront.subdomain.contact.submit' : 'storefront.contact.submit',
        ];
        
        $routeName = $routeMap[$name] ?? $name;
        
        // For subdomain routes, we need to provide subdomain as a named parameter
        // even though it's in the domain, Laravel's route() helper requires it
        if ($isSubdomain) {
            // For routes that don't have other params, we still need subdomain
            // For routes with params, remove subdomain from array params and add as named param
            $routeParams = [];
            
            // Check if this is a route that needs subdomain parameter
            $needsSubdomainParam = in_array($routeName, [
                'storefront.subdomain.preview',
                'storefront.subdomain.cart',
                'storefront.subdomain.cart.add',
                'storefront.subdomain.cart.update',
                'storefront.subdomain.cart.remove',
                'storefront.subdomain.cart.clear',
                'storefront.subdomain.checkout'
            ]);
            
            if ($needsSubdomainParam) {
                $routeParams['subdomain'] = $currentSubdomain;
            }
            
            // For routes with additional params (like product, category, dynamic)
            if (in_array($routeName, ['storefront.subdomain.product', 'storefront.subdomain.category', 'storefront.subdomain.dynamic'])) {
                // Add subdomain as named parameter (required for domain routes)
                $routeParams['subdomain'] = $currentSubdomain;
                
                // Extract slug from params
                // Params can be: [$subdomain, $slug] or just [$slug] or ['subdomain' => $subdomain, 'slug' => $slug]
                $slug = null;
                
                if (is_array($params)) {
                    // If params is associative array
                    if (isset($params['slug'])) {
                        $slug = $params['slug'];
                    } 
                    // If params is indexed array
                    elseif (count($params) === 2 && isset($params[0]) && isset($params[1])) {
                        // First param is subdomain, second is slug
                        if ($params[0] === $currentSubdomain) {
                            $slug = $params[1];
                        } else {
                            // Both params might be different, second is likely the slug
                            $slug = $params[1];
                        }
                    }
                    elseif (count($params) === 1) {
                        // Only one param - could be slug or subdomain
                        if ($params[0] !== $currentSubdomain) {
                            $slug = $params[0];
                        }
                    }
                } else {
                    // Single value, assume it's the slug
                    $slug = $params;
                }
                
                if ($slug) {
                    $routeParams['slug'] = $slug;
                }
                
                $params = $routeParams;
            } else {
                // For other routes, just use routeParams as is
                $params = $routeParams;
            }
        } else {
            // For path-based routes, ensure subdomain is first param
            // If params is empty or first param is not subdomain, add it
            if (empty($params)) {
                array_unshift($params, $currentSubdomain);
            } elseif (is_array($params) && isset($params[0])) {
                // If first param is already the subdomain, keep params as is
                // Otherwise, if first param is not subdomain, add subdomain at the beginning
                if ($params[0] !== $currentSubdomain) {
                    // Only add if the first param is not already the subdomain
                    // Check if params[0] might be a slug (not subdomain)
                    array_unshift($params, $currentSubdomain);
                }
                // If params[0] === $currentSubdomain, params are already correct
            } else {
                // Single value param, add subdomain before it
                $params = [$currentSubdomain, $params];
            }
        }
        
        // Validate params for routes that require specific parameters
        // For category routes
        if (in_array($routeName, ['storefront.subdomain.category', 'storefront.category'])) {
            $hasSlug = false;
            if ($isSubdomain) {
                $hasSlug = isset($params['slug']) && !empty($params['slug']);
            } else {
                // For path-based routes, check if params[1] exists and is not empty
                // Or if params[0] exists, is not empty, and is not the subdomain (meaning it's the slug)
                $hasSlug = (isset($params[1]) && !empty($params[1])) || 
                          (isset($params[0]) && !empty($params[0]) && $params[0] !== $currentSubdomain);
            }
            
            if (!$hasSlug) {
                \Log::error('StorefrontHelper: Missing slug parameter for category route', [
                    'route' => $routeName,
                    'original_route' => $name,
                    'params' => $params,
                    'isSubdomain' => $isSubdomain,
                    'currentSubdomain' => $currentSubdomain
                ]);
                return '#';
            }
        }
        
        // For product routes
        if (in_array($routeName, ['storefront.subdomain.product', 'storefront.product'])) {
            $hasSlug = false;
            if ($isSubdomain) {
                $hasSlug = isset($params['slug']) && !empty($params['slug']);
            } else {
                // For path-based routes, check if params[1] exists and is not empty
                // Or if params[0] exists, is not empty, and is not the subdomain (meaning it's the slug)
                $hasSlug = (isset($params[1]) && !empty($params[1])) || 
                          (isset($params[0]) && !empty($params[0]) && $params[0] !== $currentSubdomain);
            }
            
            if (!$hasSlug) {
                \Log::error('StorefrontHelper: Missing slug parameter for product route', [
                    'route' => $routeName,
                    'original_route' => $name,
                    'params' => $params,
                    'isSubdomain' => $isSubdomain,
                    'currentSubdomain' => $currentSubdomain
                ]);
                return '#';
            }
        }
        
        try {
            $generatedUrl = route($routeName, $params);
            
            // For subdomain routes, ensure we use the current request's domain instead of hardcoded .localhost
            if ($isSubdomain && in_array($routeName, ['storefront.subdomain.product', 'storefront.subdomain.category', 'storefront.subdomain.preview', 'storefront.subdomain.dynamic', 'storefront.subdomain.cart', 'storefront.subdomain.checkout'])) {
                $currentHost = request()->getHost();
                $parsedUrl = parse_url($generatedUrl);
                
                // If the generated URL has .localhost but we're on a production domain, replace it
                if (strpos($parsedUrl['host'] ?? '', '.localhost') !== false && strpos($currentHost, '.localhost') === false) {
                    // Extract subdomain from current host
                    $hostParts = explode('.', $currentHost);
                    if (count($hostParts) >= 2) {
                        $subdomain = $hostParts[0];
                        // Get the base domain (everything after the subdomain)
                        $baseDomain = implode('.', array_slice($hostParts, 1));
                        // Reconstruct URL with current domain
                        $scheme = $parsedUrl['scheme'] ?? request()->getScheme();
                        $path = $parsedUrl['path'] ?? '/';
                        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
                        $generatedUrl = $scheme . '://' . $subdomain . '.' . $baseDomain . $path . $query . $fragment;
                    }
                } elseif (strpos($parsedUrl['host'] ?? '', '.localhost') === false && strpos($currentHost, '.localhost') !== false) {
                    // If we're on localhost but URL was generated for production, use current host
                    $scheme = $parsedUrl['scheme'] ?? request()->getScheme();
                    $path = $parsedUrl['path'] ?? '/';
                    $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                    $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
                    $generatedUrl = $scheme . '://' . $currentHost . $path . $query . $fragment;
                }
            }
            
            // Get the base path from the current request (e.g., /e-manager/public)
            $basePath = request()->getBasePath();
            
            // Remove /index.php if present
            $basePath = str_replace('/index.php', '', $basePath);
            
            // Always include base path if it exists and the generated URL doesn't include it
            // This applies to both subdomain and path-based routes
            if (!empty($basePath) && $basePath !== '/' && strpos($generatedUrl, $basePath) === false) {
                // Extract the path from the generated URL
                $parsedUrl = parse_url($generatedUrl);
                $path = $parsedUrl['path'] ?? '/';
                
                // Ensure base path doesn't end with / and path starts with /
                $basePath = rtrim($basePath, '/');
                $path = '/' . ltrim($path, '/');
                
                // Reconstruct URL with base path
                $scheme = $parsedUrl['scheme'] ?? 'http';
                $host = $parsedUrl['host'] ?? '';
                $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
                
                $generatedUrl = $scheme . '://' . $host . $basePath . $path . $query . $fragment;
            }
            
            // Log for debugging (remove in production)
            if (empty($generatedUrl) || $generatedUrl === '#') {
                \Log::warning('StorefrontHelper: Generated empty or invalid URL', [
                    'route' => $routeName,
                    'params' => $params,
                    'generatedUrl' => $generatedUrl
                ]);
            }
            
            return $generatedUrl;
        } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
            \Log::error('StorefrontHelper: Route generation failed', [
                'route' => $routeName,
                'original_route' => $name,
                'params' => $params,
                'isSubdomain' => $isSubdomain,
                'currentSubdomain' => $currentSubdomain,
                'error' => $e->getMessage()
            ]);
            
            // Fallback to original route if subdomain route doesn't exist
            try {
                // Reconstruct params for fallback
                $fallbackParams = [];
                if ($isSubdomain) {
                    // For subdomain routes, try path-based route as fallback
                    if (in_array($name, ['storefront.product', 'storefront.category'])) {
                        // Reconstruct as path-based params
                        $fallbackParams = [$currentSubdomain];
                        if (isset($params['slug'])) {
                            $fallbackParams[] = $params['slug'];
                        } elseif (is_array($params) && count($params) > 0) {
                            $fallbackParams = array_merge($fallbackParams, array_values($params));
                        }
                    } else {
                        $fallbackParams = [$currentSubdomain];
                    }
                } else {
                    $fallbackParams = $params;
                    if (empty($fallbackParams) || (isset($fallbackParams[0]) && $fallbackParams[0] !== $currentSubdomain)) {
                        array_unshift($fallbackParams, $currentSubdomain);
                    }
                }
                
                $fallbackUrl = route($name, $fallbackParams);
                
                // Apply base path to fallback URL as well
                $basePath = request()->getBasePath();
                $basePath = str_replace('/index.php', '', $basePath);
                if (!empty($basePath) && $basePath !== '/' && strpos($fallbackUrl, $basePath) === false) {
                    $parsedUrl = parse_url($fallbackUrl);
                    $path = $parsedUrl['path'] ?? '/';
                    $basePath = rtrim($basePath, '/');
                    $path = '/' . ltrim($path, '/');
                    $scheme = $parsedUrl['scheme'] ?? 'http';
                    $host = $parsedUrl['host'] ?? '';
                    $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                    $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
                    $fallbackUrl = $scheme . '://' . $host . $basePath . $path . $query . $fragment;
                }
                
                return $fallbackUrl;
            } catch (\Exception $e2) {
                \Log::error('StorefrontHelper: Fallback route also failed', [
                    'route' => $name,
                    'params' => $fallbackParams ?? $params,
                    'error' => $e2->getMessage()
                ]);
                // Don't return '#' - return a constructed URL instead with base path
                $basePath = request()->getBasePath();
                $basePath = str_replace('/index.php', '', $basePath);
                $basePath = rtrim($basePath, '/');
                
                if ($isSubdomain && isset($params['slug'])) {
                    $host = request()->getHost();
                    return 'http://' . $host . $basePath . '/product/' . $params['slug'];
                }
                return '#';
            }
        } catch (\Exception $e) {
            \Log::error('StorefrontHelper: Unexpected error', [
                'route' => $routeName,
                'error' => $e->getMessage()
            ]);
            // Try to construct URL manually as last resort with base path
            $basePath = request()->getBasePath();
            $basePath = str_replace('/index.php', '', $basePath);
            $basePath = rtrim($basePath, '/');
            
            if ($isSubdomain && isset($params['slug'])) {
                $host = request()->getHost();
                return 'http://' . $host . $basePath . '/product/' . $params['slug'];
            }
            return '#';
        }
    }
}
