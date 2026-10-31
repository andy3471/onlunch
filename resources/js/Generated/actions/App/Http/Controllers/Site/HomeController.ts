import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Site\HomeController::__invoke
* @see app/Http/Controllers/Site/HomeController.php:22
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost'
*/
const HomeController = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HomeController.url(args, options),
    method: 'get',
})

HomeController.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Site\HomeController::__invoke
* @see app/Http/Controllers/Site/HomeController.php:22
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost'
*/
HomeController.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args?.tenant ?? '$site',
    }

    return HomeController.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\HomeController::__invoke
* @see app/Http/Controllers/Site/HomeController.php:22
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost'
*/
HomeController.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HomeController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Site\HomeController::__invoke
* @see app/Http/Controllers/Site/HomeController.php:22
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost'
*/
HomeController.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: HomeController.url(args, options),
    method: 'head',
})

export default HomeController