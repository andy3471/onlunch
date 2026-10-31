import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
const HomeController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HomeController.url(options),
    method: 'get',
})

HomeController.definition = {
    methods: ["get","head"],
    url: '//localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
HomeController.url = (options?: RouteQueryOptions) => {
    return HomeController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
HomeController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HomeController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
HomeController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: HomeController.url(options),
    method: 'head',
})

export default HomeController