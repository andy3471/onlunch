import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/sites/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//localhost/sites/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/sites/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/sites/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::create
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:22
* @route '//localhost/sites/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/sites'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//localhost/sites',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/sites'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Brochure\SiteRegistrationController::store
* @see app/Http/Controllers/Brochure/SiteRegistrationController.php:27
* @route '//localhost/sites'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

const sites = {
    create: Object.assign(create, create),
    store: Object.assign(store, store),
}

export default sites