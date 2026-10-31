import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '//localhost',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Brochure\HomeController::__invoke
* @see app/Http/Controllers/Brochure/HomeController.php:13
* @route '//localhost'
*/
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

const brochure = {
    home: Object.assign(home, home),
}

export default brochure