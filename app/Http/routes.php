<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'role:admin']], function () {
		Route::resource('company-categories', 'CompanyCategoriesController');
		Route::resource('job-categories', 'JobCategoriesController');
		Route::resource('contract-types', 'ContractTypesController');
		Route::resource('skills', 'SkillsController');
		Route::controller('stats', 'StatsController');

		// Route::resource('jobs', 'JobsController', ['only' => ['index', 'show']]);
	});

	Route::group(['namespace' => 'Portal'], function () {
		//Jobseekers
		Route::group(['middleware' => ['auth', 'role:jobseeker;admin']], function () {
			Route::resource('resumes', 'ResumesController', ['except' => ['destroy']]);
			Route::get('resumes/{resumes}/applications', [
				'as' 	=> 'resumes.applications',
				'uses' 	=> 'ResumesController@applications'
			]);
			Route::post('companies/{companies}/jobs/{jobs}/apply', [
				'as' 	=> 'companies.jobs.apply',
				'uses' 	=> 'CompaniesJobsController@apply'
			]);
		});

		Route::group(['middleware' => ['auth', 'role:jobseeker']], function () {
			Route::get('my-resume', [
				'as' 	=> 'jobseeker.resume',
				'uses'	=> 'ResumesController@myResume'
			]);
		});

		//Empolyers
		Route::group(['middleware' => ['auth', 'role:employer;admin']], function () {
			Route::resource('companies', 'CompaniesController', ['only' => ['edit', 'update']]);
			Route::resource('companies.jobs', 'CompaniesJobsController', ['except' => ['index', 'destroy']]);
		});

		Route::group(['middleware' => ['auth', 'role:employer']], function () {
			Route::get('my-company', [
				'as' 	=> 'employer.company',
				'uses'	=> 'CompaniesController@myCompany'
			]);
		});

		//All Users
		Route::resource('resumes', 'ResumesController', ['only' => ['index', 'show']]);
		Route::post('search/resumes', [
			'as' 	=> 'resumes.search',
			'uses' 	=> 'ResumesController@search'
		]);
		Route::resource('jobs', 'JobsController', ['only' => ['index', 'show']]);
		Route::get('search/jobs', [
			'as' 	=> 'jobs.search',
			'uses' 	=> 'JobsController@search'
		]);

		Route::resource('companies', 'CompaniesController', ['only' => ['index', 'show']]);

		/*Route::post('search/companies', [
			'as' 	=> 'companies.search',
			'uses' 	=> 'CompaniesController@search'
		]);*/
		Route::resource('companies.jobs', 'CompaniesJobsController', ['only' => ['index', 'show']]);

		/*Route::post('search/companies/{companies}/jobs', [
			'as' 	=> 'companies.jobs.search',
			'uses' 	=> 'CompaniesJobsController@search'
		]);*/

	});
});

