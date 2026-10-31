import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::register
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '//localhost/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::register
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::register
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::register
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/register'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//localhost/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/register'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/register'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

const sites = {
    register: Object.assign(register, register),
    store: Object.assign(store, store),
}

export default sites