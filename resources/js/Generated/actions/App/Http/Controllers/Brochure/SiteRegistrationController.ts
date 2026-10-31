import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//localhost/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/register'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
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

const SiteRegistrationController = { create, store }

export default SiteRegistrationController