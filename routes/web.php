<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
    "Working on it Bro
    -Maizied"

*/
// Authentication Blades Check

Route::get('/authlog', function () { return view('authentication.login'); });
Route::get('/authreg', function () { return view('authentication.register_request'); });
Route::get('/authregi', function () { return view('authentication.register'); });
Route::get('/authregis', function () { return view('authentication.register_completed'); });
Route::get('/authregist', function () { return view('authentication.login-project-details'); });
Route::get('/pass', function () { return view('authentication.passwords.email'); });
Route::get('/reset', function () { return view('authentication.passwords.reset'); });
Route::get('/ereset', function () { return view('authentication.email.reset'); });
Route::get('/regstep1', function () { return view('authentication.email.registration_step1'); });
Route::get('/regstep2', function () { return view('authentication.email.registration_step2'); });
// Route::get('/service_details', function () { return view('systems.servicedetails'); });



//Homepage Controller
Route::get('/homepage', 'User\HomeController@homepage')->name('homepage');

// Authentication Routes
Route::get('/register-request', 'User\AuthController@registerRequest')->name('user-register-request');
Route::post('/register-request', 'User\AuthController@registerRequestAction')->name('user-register-request-action');
Route::get('/register/{token}', 'User\AuthController@register')->name('user-register');
Route::post('/register/{token}', 'User\AuthController@registerAction')->name('user-register-action');
Route::get('/login', 'User\HomeController@loginmethod')->name('login');
Route::post('/login', 'User\HomeController@loginAction')->name('loginAction');
Route::get('/logout', 'User\HomeController@logout')->name('user-logout');

//Service Details Controller
// Route::get('/service-details/{id}', 'User\ServiceProductController@serviceDetails')->name('service-details');   //Extra Design//
Route::get('/service_details/{id}', 'User\ServiceProductController@serviceDetails')->name('service_details');


//ServiceController
Route::get('/addservice', 'User\ServiceProductController@loadForm')->name('get-add-service');
Route::post('/addservice', 'User\ServiceProductController@storeProduct')->name('add-service');

//RequestController
Route::get('/addrequest', 'User\RequestController@loadRequestForm')->name('get-add-request');
Route::post('/addrequest', 'User\RequestController@storeRequest')->name('add-request');
Route::get('/myrequest', 'User\RequestController@myrequest')->name('my-request');
Route::get('/myrequestdetails/{id}', 'User\RequestController@myrequestdetails')->name('my-request-details');

// Profile Controller
Route::get('/myprofile', 'User\ProfileController@myProfile')->name('my-profile');
Route::get('/visitprofile', 'User\ProfileController@visitProfile')->name('visit-profile');

//Profile update routes
Route::post('/updatePic', 'User\ProfileController@updatePic')->name('update-pic');
Route::post('/basic-update', 'User\ProfileController@updatebasic')->name('update-basic');
Route::post('/intro-update', 'User\ProfileController@updateintro')->name('intro-basic');
Route::post('/payment-info-update', 'User\ProfileController@updatepayment')->name('update-payment');




Route::get('/phpinfo', function(){
	phpinfo();
});

Route::get('/', 'HomeController@index')->name('home');


Route::get('/test', 'API\TestController@test');
Route::get('/test_dis', function(){
	$Affiliator = new App\Libraries\Affiliator();
	$Affiliator->commission(100, 77, 50);
});
Route::get('/test_ranking', function(){
	$Affiliator = new App\Libraries\Affiliator();
	$Affiliator->rankingBonus();
});
Route::get('/test_recruiting', function(){
	$Affiliator = new App\Libraries\Affiliator();
	$Affiliator->recruiting_bonus();
});
//Debug
Route::get('/child_count', function(){
	$Affiliator = new App\Libraries\Affiliator();
	$Affiliator->rankCheck(27);
});




// Route::get('/backend/notification', 'PusherController@index')->name('notification');
// Route::get('/backend/my-event', 'PusherController@sendNotification')->name('my-event');

// Route::get('laravel-send-email', 'TestController@sendEmail');

// Route::get('/backend/test', 'HomeController@test')->name('test');
// Route::get('/backend/reset', 'Backend\TestController@reset');
// Route::get('/backend/add-aff', 'Backend\TestController@add_aff');
// Route::get('/backend/testing', 'Backend\TestController@getlist');
// Route::get('/backend/stack', 'Backend\TestController@stack');
// Route::get('/backend/see/{pass}', 'Backend\TestController@see');
// Route::post('/backend/home', 'HomeController@index')->name('home');

// Route::get('/backend/array', 'API\AffiliatorTreeController@test');
//============================ API Routes (Site) ===================================
//
Route::get('/aff/{ref_code}', 'HomeController@index');
Route::get('/about', 'HomeController@index');
Route::get('/faq', 'HomeController@index');
Route::get('/search-results/{id}', 'HomeController@index');
Route::get('/logina', 'HomeController@index');
Route::get('/logina/seller', 'HomeController@index');
Route::get('/logina/affiliator', 'HomeController@index');
Route::get('/seller-registration-step-1', 'HomeController@index');
Route::get('/seller-registration-step-2', 'HomeController@index');
Route::get('/buyer-registration-step-1', 'HomeController@index');
Route::get('/Buyer-registration-step-2', 'HomeController@index');
Route::get('/affiliator/{name}/{ref_code}', 'HomeController@index');
Route::get('/registration-success-message', 'HomeController@index');
Route::get('/password-reset', 'HomeController@index');
Route::get('/password-reset/reset-email-sent', 'HomeController@index');
Route::get('/enter-new-password/{code}', 'HomeController@index');
Route::get('/categories/{id}', 'HomeController@index');
Route::get('/sub-categories/{id}', 'HomeController@index');
Route::get('/request-details-from-bulletin/{id}', 'HomeController@index');
Route::get('/categories-grid', 'HomeController@index');
Route::get('/categories-details/{id}', 'HomeController@index');
Route::get('/payment/{id}', 'HomeController@index');
Route::get('/buyer-dashboard', 'HomeController@index');
Route::get('/buyer-direct-message/{id}', 'HomeController@index');
Route::get('/buyer-direct-message-list', 'HomeController@index');
Route::get('/buyer-favorites', 'HomeController@index');
Route::get('/buyer-change-password', 'HomeController@index');
Route::get('/chat-initiation', 'HomeController@index');
Route::get('/affiliator-dashboard', 'HomeController@index');
Route::get('/affiliator-individual-products', 'HomeController@index');
Route::get('/affiliator-create-service-link', 'HomeController@index');
Route::get('/affiliator-create-link', 'HomeController@index');
Route::get('/affiliator-management', 'HomeController@index');
Route::get('/affiliator-sales-management', 'HomeController@index');
Route::get('/affiliator-management-of-child', 'HomeController@index');
Route::get('/affiliator-change-password', 'HomeController@index');
Route::get('/affiliator-referral-link', 'HomeController@index');
Route::get('/affiliator-mlm-tree', 'HomeController@index');
Route::get('/affiliator-direct-message-list', 'HomeController@index');
Route::get('/affiliator-direct-message/{id}', 'HomeController@index');
Route::get('/affiliator-favorites', 'HomeController@index');
Route::get('/aff-product-detail-from-link/{id}/{id1}', 'HomeController@index');
Route::get('/aff-reqcuitment-bonus', 'HomeController@index');
Route::get('/aff-ranking-bonus', 'HomeController@index');
Route::get('/aff-lottery-bonus', 'HomeController@index');
Route::get('/affiliator-purchase-list', 'HomeController@index');
Route::get('/affiliator-chat-initiation', 'HomeController@index');
Route::get('/seller-dashboard', 'HomeController@index');
Route::get('/product-upload', 'HomeController@index');
Route::get('/product-upload-confirm', 'HomeController@index');
Route::get('/product-upload-confirm-edit', 'HomeController@index');
Route::get('/edit-product-upload/{id}', 'HomeController@index');
Route::get('/service-list', 'HomeController@index');
Route::get('/purchase-list', 'HomeController@index');
Route::get('/seller-favorites', 'HomeController@index');
Route::get('/seller-direct-message-list', 'HomeController@index');
Route::get('/seller-buyer-direct-message-list', 'HomeController@index');
Route::get('/seller-direct-message', 'HomeController@index');
Route::get('/seller-buyer-direct-message', 'HomeController@index');
Route::get('/seller-change-password', 'HomeController@index');
Route::get('/sales', 'HomeController@index');
Route::get('/seller-chat-initiation', 'HomeController@index');
Route::get('/seller-transaction', 'HomeController@index');
Route::get('/seller-estimate', 'HomeController@index');
Route::get('/estimate-details', 'HomeController@index');
Route::get('/seller-complete-cancel', 'HomeController@index');
Route::get('/buyer-transaction', 'HomeController@index');
Route::get('/buyer-estimate', 'HomeController@index');
Route::get('/buyer-estimate-alt', 'HomeController@index');
Route::get('/buyer-complete-cancel', 'HomeController@index');
Route::get('/buyer-ask-estimate/{id}', 'HomeController@index');
Route::get('/buyer-ask-estimate-edit/{id}', 'HomeController@index');
Route::get('/seller-buyer-transaction', 'HomeController@index');
Route::get('/seller-buyer-estimate', 'HomeController@index');
Route::get('/seller-buyer-estimate-alt', 'HomeController@index');
Route::get('/seller-buyer-complete-cancel', 'HomeController@index');
Route::get('/affiliator-buyer-complete-cancel', 'HomeController@index');
Route::get('/affiliator-buyer-estimate', 'HomeController@index');
Route::get('/affiliator-buyer-estimate-alt', 'HomeController@index');
Route::get('/affiliator-buyer-transaction', 'HomeController@index');
Route::get('/seller-edit-profile', 'HomeController@index');
Route::get('/seller-edit-intro', 'HomeController@index');
Route::get('/seller-edit-portfolio', 'HomeController@index');
Route::get('/my-profile/users/{id}', 'HomeController@index');
Route::get('/buyer-my-profile', 'HomeController@index');
Route::get('/buyer-my-profile-view/users/{id}', 'HomeController@index');
Route::get('/affiliator-my-profile', 'HomeController@index');
Route::get('/my-profile-view/users/{id}', 'HomeController@index');
Route::get('/seller-service-list/{id}', 'HomeController@index');
Route::get('/my-portfolio', 'HomeController@index');
Route::get('/my-portfolio-view', 'HomeController@index');
Route::get('/request', 'HomeController@index');
Route::get('/request-bulletin', 'HomeController@index');
Route::get('/post-request', 'HomeController@index');
Route::get('/request-home', 'HomeController@index');
Route::get('/request-confirm', 'HomeController@index');
Route::get('/request-details/{id}', 'HomeController@index');
Route::get('/request-details-confirm/{id}', 'HomeController@index');
Route::get('/buyer-request-home', 'HomeController@index');
Route::get('/post-request-buyer', 'HomeController@index');
Route::get('/buyer-request-details', 'HomeController@index');
Route::get('/seller-buyer-request-details', 'HomeController@index');
Route::get('/seller-buyer-request-home', 'HomeController@index');
Route::get('/seller-post-request-buyer', 'HomeController@index');
Route::get('/seller-request-details', 'HomeController@index');
Route::get('/affiliator-buyer-request-home', 'HomeController@index');
Route::get('/affiliator-post-request-buyer', 'HomeController@index');
Route::get('/affiliator-request-details', 'HomeController@index');
Route::get('/all-notification', 'HomeController@index');
Route::get('/system-notification', 'HomeController@index');
Route::get('/purchase-notification', 'HomeController@index');
Route::get('/request-notification-receive', 'HomeController@index');
Route::get('/request-notification-decline', 'HomeController@index');
Route::get('/request-notification-serve', 'HomeController@index');
Route::get('/request-notification-accept', 'HomeController@index');
Route::get('/request-notification-estimation', 'HomeController@index');
Route::get('/request-notification-close', 'HomeController@index');
Route::get('/follow-notification', 'HomeController@index');
Route::get('/followers', 'HomeController@index');
Route::get('/followers-buyer', 'HomeController@index');
Route::get('/seller-followers-buyer', 'HomeController@index');
Route::get('/affiliator-followers-buyer', 'HomeController@index');
Route::get('/my-points', 'HomeController@index');
Route::get('/affiliator-my-points', 'HomeController@index');
Route::get('/configuration', 'HomeController@index');
Route::get('/configuration-buyer', 'HomeController@index');
Route::get('/Privacy-policy', 'HomeController@index');
Route::get('/terms-conditions', 'HomeController@index');
Route::get('/laws-regulations', 'HomeController@index');
Route::get('/transaction-flow', 'HomeController@index');
Route::get('/portfolio-details/{id}', 'HomeController@index');
Route::get('/com-with-sys', 'HomeController@index');
Route::get('/contact-us', 'HomeController@index');
Route::get('/new-childern-thismonth', 'HomeController@index');
Route::get('/active-childern-thismonth', 'HomeController@index');
Route::get('/buyer-request-estimation-details', 'HomeController@index');
//============================ API Routes (Site) ===================================
// total: 154

	// Temp top page
	Route::get('/backend/get-service-top', 'API\DisplayController@temp_top');
	// Gateway Kicks
	Route::post('/retreat', 'API\PurchaseController@retreat');
	Route::get('/bank-kick', 'API\PurchaseController@kick_bank');


	Route::get('/laravel-send-email', 'API\TestController@sendEmail');
	//Tree
	Route::get('/backend/get-tree/{id}', 'API\AffiliatorTreeController@tree_data');

	// Login-reg routes
	Route::post('/backend/seller-login','API\RegController@seller_log');
	Route::post('/backend/seller-reg-first','API\RegController@seller_reg_first');
	Route::post('/backend/seller-reg-second','API\RegController@seller_reg_second');
	Route::get('/backend/activate-seller-account/{code}', 'API\RegController@activateSellerAccount')->name('activate-seller-account');
	Route::get('/backend/activate-buyer-account/{code}', 'API\RegController@activateSellerAccount')->name('activate-buyer-account');
	Route::post('/backend/check-mail','API\RegController@check_email');

	// buyer login-reg routes
	Route::post('/backend/buyer-login','API\BuyerController@buyer_log');
	Route::post('/backend/buyer-reg-first','API\BuyerController@buyer_reg_first');
	Route::post('/backend/buyer-reg-second','API\BuyerController@buyer_reg_second');
	Route::get('/backend/activate-buyer-account/{code}', 'API\BuyerController@activateBuyerAccount')->name('activate-buyer-account');

	// product upload routes
	Route::get('/backend/category-list','API\UploadController@get_drop_downs_at_load');
	Route::get('/backend/category-list-dropdown','API\UploadController@get_drop_downs_at_load_cat');
	Route::get('/backend/sub-category-list/{id}','API\UploadController@get_drop_down_onchange');
	Route::post('/backend/upload-product','API\UploadController@store');

	Route::get('/backend/get-for-edit/{id}','API\UploadController@get_for_edit');

	// product/seller display
	Route::get('/backend/seller-product/{id}/{layer}/{flag}','API\DisplayController@display');
	Route::get('/backend/seller-product-mobile/{id}/{layer}','API\DisplayController@display_mobile');
	Route::get('/backend/seller-product-affs-only/{id}/{layer}','API\DisplayController@display_only_affs');
	Route::get('/backend/category-product/{id}/{layer}','API\DisplayController@display_see_more_cat');
	Route::get('/backend/category-product-temp','API\DisplayController@display_see_more_cat_temp');  //temp see all
	Route::get('/backend/category-product-affs/{id}/{layer}','API\DisplayController@display_see_more_cat_aff');
	Route::get('/backend/category-product-affs-temp','API\DisplayController@display_see_more_cat_aff_temp'); //temp affi see all
	Route::get('/backend/subcategory-product/{id}/{layer}','API\DisplayController@display_see_more_sub_cat');
	Route::get('/backend/subcategory-product-affs/{id}/{layer}','API\DisplayController@display_see_more_sub_cat_affs');
	Route::get('/backend/seller-self-products/{id}/{layer}','API\DisplayController@display_seller_products');
	Route::get('/backend/single-product/{id}/{device}','API\DisplayController@display_product_details');
	Route::get('/backend/affiliator-eligibility/{product_id}/{buyer_id}','API\PurchaseController@aff_eli');
	Route::get('/backend/details-slider/{id}/{layer}/{device}','API\DisplayController@details_slider');
	Route::get('/backend/seller-pro','API\DisplayController@display_pro_sellers');

	// Accounts info
	Route::get('/backend/accounts-info/{id}','API\AccountsController@display_accounts');

	// Profile
	Route::post('/backend/config-profile','API\ProfileController@configure');
	Route::get('/backend/get-config-profile/{id}','API\ProfileController@get_configure');

	Route::get('/backend/age-groups','API\AccountsController@get_age_group');
	Route::post('/backend/update-basic-profile','API\ProfileController@update_basic');
	Route::get('/backend/get-basic-profile/{id}','API\ProfileController@get_my_basic');
	Route::post('/backend/change-password','API\ProfileController@change_password');

	Route::post('/backend/upload-personal-text','API\ProfileController@update_my_text');
	Route::get('/backend/get-personal-text/{id}','API\ProfileController@get_my_personal_text');
	Route::post('/backend/upload-portfolio-item','API\ProfileController@add_portfolio');

	Route::get('/backend/get-service-profile/{id}','API\ProfileController@get_my_services');
	Route::get('/backend/get-portfolio-profile/{id}','API\ProfileController@get_my_portfolio');

	Route::get('/backend/get-profile/{id}','API\ProfileController@get_profile_info');
	Route::get('/backend/get-portfolio-all/{id}','API\ProfileController@get_portfolio_all');
	Route::get('/backend/check-username-dup/{name}/{id}','API\ProfileController@check_username');

	// Request board
	Route::post('/backend/request-board','API\RequestController@store');
	Route::get('/backend/my-request-board/{id}','API\RequestController@myRequestBoard');
	Route::get('/backend/get-budget-list','API\RequestController@get_budget_list');
	Route::get('/backend/get-requests/{user}/{layer}','API\RequestController@get_all_requests_i_responded_to');
	Route::get('/backend/get-bulletin/{user}/{layer}','API\RequestController@bulletin_board');
	Route::get('/backend/get-requests-details/{id}/{user}','API\RequestController@get_request_detail');
	Route::post('/backend/post-requests-response','API\RequestController@store_response');
	Route::get('/backend/get-my-req-list/{id}','API\RequestController@get_for_estimation_page');
	Route::get('/backend/get-range/{id}','API\RequestController@get_range');

	Route::get('/backend/get-requests-response/{id}','API\RequestController@get_all_response');
	Route::post('/backend/accept-requests-response','API\RequestController@accept');

	Route::post('/backend/acknowledge-service-receival','API\RequestController@receive');
	Route::post('/backend/send-service','API\RequestController@send');

	Route::post('/backend/get-names-for-confs','API\RequestController@get_names');
	Route::get('/backend/request-details-purchase/{id}/{seller}','API\RequestController@details_for_purchase');


	// Followers
	Route::get('/backend/seller-followers/{id}','API\FollowerController@display_my_followers');
	Route::get('/backend/buyer-followings/{id}','API\FollowerController@display_my_followings');

	Route::get('/backend/follow-seller/{seller_id}/{buyer_id}','API\FollowerController@follow');
	Route::get('/backend/unfollow-seller/{seller_id}/{buyer_id}','API\FollowerController@unfollow');
	Route::get('/backend/check-follow-status/{seller}/{buyer}','API\FollowerController@check_status');

	// Transaction/estimates
	Route::get('/backend/buyer-estimates/{id}','API\TransactionController@my_estimates');
	Route::get('/backend/seller-all-estimates','API\TransactionController@all_estimates');

	// search
	Route::get('/backend/search-result/{searchkey}/{layer}','API\SearchController@search_result');
	Route::post('/backend/get-filtered-requests','API\RequestController@get_filtered_reqs');
	Route::post('/backend/get-particular-child','API\AffiliatorController@get_particular_child');
	Route::post('/backend/get-aff-sales','API\AffiliatorSalesController@get_filtered_sale');

	// Residential area
	Route::get('/backend/residentials','API\DisplayController@residentials');

	Route::get('/backend/sex','API\DisplayController@sex');

	// top slider
	Route::get('/backend/top-slider','API\TopSliderController@show');

	// Favourites
	Route::get('/backend/add-to-favourite/{product_id}/{user_id}','API\FavouriteController@add_to_favourite');
	Route::get('/backend/get-favourite-count/{product_id}/{user_id}','API\FavouriteController@get_favourite_count');
	Route::get('/backend/remove-from-favourites/{product_id}/{user_id}','API\FavouriteController@remove_from_fav');
	Route::get('/backend/get-my-favourites/{user_id}/{layer}','API\FavouriteController@get_my_favourites');

	// Dashboards
	Route::get('/backend/buyer-dashboard/{user_id}/{layer}','API\DashboardController@buyer_dashboard');


	//Seller Dahboard
	Route::get('/backend/seller-dashboard/{id}','API\SellerDashboardController@seller_dashboard');




	// Purchase
	Route::post('/backend/purchase','API\PurchaseController@store');
	Route::post('/backend/cancel-payment','API\PurchaseController@cancelPayment');
	Route::post('/backend/execute-response-acceptance','API\PurchaseController@execute_response_acceptance');

	Route::get('/backend/seller-purchase/{id}','API\PurchaseController@seller_purchase_history');
	Route::get('/backend/buyer-purchase/{id}','API\PurchaseController@buyer_purchase_history');

	Route::get('/backend/get-av-points/{id}','API\PurchaseController@get_my_avs');

	//Statistics
	Route::get('/backend/seller-sold/{id}','API\StatisticsController@seller_sales_history');

	// affiliator registration
	Route::post('/backend/aff-registration','API\AffiliatorController@affiliator_reg');
	Route::get('/backend/aff-tree/{id}','API\AffiliatorTreeController@tree');
	Route::post('/backend/aff-log','API\AffiliatorController@aff_log');

	// affiliator links
	Route::post('/backend/universal-link','API\AffiliatorController@universal_link');

	Route::get('/backend/aff-ref-link/{id}','API\AffiliatorLinkController@get_link');
	Route::post('/backend/add-my-queue','API\AffiliatorController@add_to_my_queue');
	Route::post('/backend/aff-list-for-queue','API\AffiliatorQueueController@get_list_to_aff');
	Route::get('/backend/aff-list-for-queue-all/{id}/{name}/{layer}','API\AffiliatorQueueController@get_list_to_aff_all');

	Route::get('/backend/aff-product-detail-from-link/{pro_id}/{aff_string}','API\AffiliatorController@get_product_from_link');
	Route::get('/backend/get-aff-id/{id}','API\AffiliatorController@get_aff_id');
	Route::get('/backend/get-aff-personal-queue/{id}/{layer}','API\AffiliatorQueueController@get_my_queue');

	Route::get('/backend/get-my-sales/{id}/{logged_user}/{layer}','API\AffiliatorSalesController@get_my_sales');
	Route::get('/backend/get-my-decendants/{id}','API\AffiliatorSalesController@get_all_decendants');

	Route::get('/backend/my-commission-sources/{aff_id}/{layer}','API\AffiliatorSalesController@get_my_commissions_list');
	Route::get('/backend/my-commission-total/{aff_id}','API\AffiliatorSalesController@get_my_commissions');
	Route::get('/backend/my-affiliating-total/{aff_id}','API\AffiliatorSalesController@total_affiliating');

	// Ratings
	Route::post('/backend/rate','API\RatingController@give_rating');

	// Review
	Route::post('/backend/review','API\ReviewController@add_review');
	Route::get('/backend/get-all-review/{id}/{buyer}','API\ReviewController@get_review');

	// Direct message
	//seller
	Route::get('/backend/get-all-chated-buyer/{buyer_id}/{seller_id}','API\ChatController@get_chated_list_as_buyer');
	Route::get('/backend/get-all-chated-seller/{buyer_id}/{seller_id}','API\ChatController@get_chated_list_as_seller');
	Route::get('/backend/get-threads-buyer/{id}','API\ChatController@get_threads_buyer');
	Route::get('/backend/get-all-threads-seller/{id}','API\ChatController@get_threads_seller');
	//buyer
	Route::get('/backend/get-all-chated-buyer-solo/{buyer_id}/{seller_id}','API\ChatController@get_chated_list_as_buyer_solo');
	Route::get('/backend/get-threads-buyer-solo/{id}','API\ChatController@get_threads_buyer_solo');
	Route::post('/backend/store-msg-initiation','API\ChatController@initiate_chat');

	Route::get('/backend/get-unread-chat-count/{id}','API\ChatController@get_unread_notification_count');
	Route::get('/backend/open-msg/{id}/{sender_id}','API\ChatController@open');
	Route::get('/backend/get-all-chated-sys/{id}','API\ChatController@get_all_chated_sys');

	Route::get('/backend/get-thread-unopened/{receiver}/{sender}','API\ChatController@check_unopened');
	//affiliator

	//Common
	Route::post('/backend/store-msg','API\ChatController@store_plain_msg');
	Route::post('/backend/store-system-msg','API\ChatController@store_automated_msg');

	//Purchase Transaction
	Route::get('/backend/get-my-estimation-all/{id}','API\PurchaseTransactionController@get_during_estimations_buyer');
	Route::get('/backend/get-my-tradings-all/{id}','API\PurchaseTransactionController@get_during_trading_buyer');
	Route::get('/backend/get-my-completed-all/{id}','API\PurchaseTransactionController@get_completed_buyer');

	Route::get('/backend/get-my-estimation-seller/{id}','API\PurchaseTransactionController@get_during_estimations_seller');
	Route::get('/backend/get-my-tradings-seller/{id}','API\PurchaseTransactionController@get_during_trading_seller');
	Route::get('/backend/get-my-completed-seller/{id}','API\PurchaseTransactionController@get_completed_seller');

	//Cookie altenate
	Route::post('/backend/cookie-alternate','API\PurchaseTransactionController@get_cookies');
	Route::get('/backend/cookie-history/{user_id}/{product_id}','API\PurchaseTransactionController@get_cookie_aff');

	//Link validation
	Route::post('/backend/valid-link','API\PurchaseTransactionController@link_validation');

	//Affiliator Bonus of all kinds
	Route::get('/backend/get-recruiting-bonus/{user_id}','API\AffiliatorBonusController@get_rbonus_aff');
	Route::get('/backend/get-ranking-bonus/{user_id}','API\AffiliatorBonusController@get_rankbonus_aff');
	Route::get('/backend/get-lottery-bonus/{user_id}','API\AffiliatorBonusController@get_aff');
	Route::get('/backend/get-eligibility-stat/{user_id}','API\AffiliatorBonusController@get_eli_stat');
	Route::get('/backend/new-children-list/{user_id}','API\AffiliatorBonusController@get_new_child');
	Route::get('/backend/new-active-children-list/{user_id}','API\AffiliatorBonusController@get_new_active_child');
	Route::get('/backend/get-dashboard-bonus/{user_id}','API\AffiliatorBonusController@for_dashboard');
	Route::post('/backend/get-aff-bonus-filtered-ranking','API\AffiliatorBonusController@search_bonus_ranking');
	Route::post('/backend/get-aff-bonus-filtered-recruit','API\AffiliatorBonusController@search_bonus_recruit');

	// Validations
	Route::post('/backend/valid-name-test','API\UploadController@name_validation');

	//Google login
	Route::post('/backend/check-ex-email','API\SNSController@check_email');

	//Notification
	Route::get('/backend/make-system-notification/{user_id}/{notification_id}','API\NotificationController@system_notification');
	Route::get('/backend/purchase-notification/{user_id}/{notification_id}','API\NotificationController@purchase_notification');
	Route::get('/backend/notify-request-receive/{user_id}/{notification_id}','API\NotificationController@request_receive_notification');
	Route::get('/backend/notify-request-acceptance/{user_id}/{notification_id}','API\NotificationController@request_accept_notification');
	Route::get('/backend/notify-request-decline/{user_id}/{notification_id}','API\NotificationController@request_decline_notification');
	Route::get('/backend/notify-response-acceptance/{user_id}/{notification_id}','API\NotificationController@response_accept_notification');
	Route::get('/backend/notify-service-delivery/{user_id}/{notification_id}','API\NotificationController@service_served_notification');
	Route::get('/backend/get-all-notification/{user_id}','API\NotificationController@get_notification');
	Route::get('/backend/notification-status-change/{user_id}/{notification_id}','API\NotificationController@change_notification_status');
	Route::get('/backend/unread-notification-count/{user_id}','API\NotificationController@get_all_notification');
	Route::get('/backend/recruitment-expired/{user_id}/{notification_id}','API\NotificationController@rec_expired');
	Route::get('/backend/estimation-asked/{user_id}/{notification_id}','API\NotificationController@new_estimation');
	Route::get('/backend/following/{user_id}/{notification_id}','API\NotificationController@following');
	Route::get('/backend/open/{user_id}','API\NotificationController@open_all');

	//Unefficients
	Route::get('/backend/get-cat-name/{id}','API\UploadController@get_cat_name');
	Route::get('/backend/get-aff-reward/{id}','API\UploadController@get_reward_amount');

	//Static page data
	Route::get('/backend/get-static/{id}','API\StaticController@get_static');

	//Point to money transfer
	Route::post('/backend/apply-for-withdrawal','API\AccountsController@withdraw');
	Route::get('/backend/withdrawal-history/{id}','API\AccountsController@withdraw_history');
	Route::get('/backend/check-legality/{id}','API\AccountsController@profile_check');

	//Portfolio
	Route::get('/backend/get-portfolio/{id}','API\DisplayController@get_porifolio');

	//Contact us
	Route::post('/backend/store-contact-data','API\UploadController@store_contact_us');
	Route::post('/backend/store-affiliator-application','API\UploadController@store_application');

	//Forget password
	Route::post('/backend/send-password-reset-link','API\ProfileController@i_forgot');
	Route::post('/backend/save-new-pass','API\ProfileController@save_pass');
	Route::get('/backend/check','API\ProfileController@check_link');
	//============================ API Routes (Admin) ===================================
	// total: 15

	//Service List
	// Route::get('/total-service','ADMIN\ServiceListController@get_total');
	// Route::get('/service-list','ADMIN\ServiceListController@get_service_list');
	// Route::get('/service-details/{service_id}','ADMIN\ServiceListController@get_service_details');

	// //Sales
	// Route::get('/system-earning','ADMIN\TotalSalesController@total_system_earning');
	// Route::get('/money-statistics','ADMIN\TotalSalesController@get_stats');

	// //Withdraw requests
	// Route::get('/pending-count','ADMIN\WithdrawRequestController@total_pending_requests');
	// Route::get('/pending-list','ADMIN\WithdrawRequestController@get_pending_requests');

	// //Seller info
	// Route::get('/seller-count','ADMIN\SellerController@total_seller');
	// Route::get('/seller-list','ADMIN\SellerController@seller_list');
	// Route::get('/seller-basic-info/{seller_id}','ADMIN\SellerController@seller_basic_info');
	// Route::get('/seller-remaining/{seller_id}','ADMIN\SellerController@seller_remaining_amount');
	// Route::get('/seller-withdraw-requests/{seller_id}','ADMIN\SellerController@seller_requests');
	// Route::get('/seller-sales/{seller_id}','ADMIN\SellerController@sales');

	// //Affiliator info
	// Route::get('/affiliator-count','ADMIN\AffiliatorController@total_affiliator');
	// Route::get('/affiliator-list','ADMIN\AffiliatorController@aff_list');




//Auth::routes();
//============================ Backend Routes ===================================
Route::group(['prefix' => 'admin' , 'middleware'=> ['web']], function () {

	Route::get('/login', 'Admin\AuthController@login')->name('admin-login');
	Route::get('/apply-affiliator-link', 'Admin\AffiliatorController@applications')->name('affiliator-application-list');
	Route::get('/apply-list', 'Admin\AffiliatorController@affiliatorApplicationListData')->name('affiliator-application-list-data');

	Route::post('/login', 'Admin\AuthController@loginAction')->name('admin-login-action');
	Route::get('/logout', 'Admin\AuthController@logout')->name('admin-logout');

	Route::group(['middleware' => 'admin-auth'], function () {
		Route::get('/', 'Admin\DashboardController@index')->name('admin-dashboard');
		Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin-dashboard1');
		Route::get('/new-admin', 'Admin\AdminController@newAdmin')->name('new-admin-add');
		Route::post('/new-admin', 'Admin\AdminController@newAdminAction')->name('new-admin-add-action');
		Route::get('/all-admin', 'Admin\AdminController@allAdmin')->name('admin-list');
		Route::get('/edit-admin/{id}', 'Admin\AdminController@edit')->name('edit-admin');
		Route::post('/edit-admin', 'Admin\AdminController@editAction')->name('admin-edit-action');
		Route::get('/delete-admin/{id}', 'Admin\AdminController@delete')->name('delete-admin');

		Route::group(['prefix' => 'service'], function () {
			Route::get('/', 'Admin\ServiceController@index')->name('admin-service-list');
			Route::get('/list-data', 'Admin\ServiceController@listData')->name('admin-service-list-data');
			Route::get('/service-details', 'Admin\ServiceController@details')->name('admin-service-details');
			Route::get('/service-banner', 'Admin\ServiceController@banner')->name('admin-service-banner');
			Route::post('/save-banner', 'Admin\ServiceController@banner_save')->name('admin-service-banner-save');
			Route::get('/change-status/{id}/{status}', 'Admin\ServiceController@changeStatus')->name('admin-service-change-status');
			Route::get('/change-status-banner/{id}', 'Admin\ServiceController@deleteBanner')->name('admin-banner-change-status');
		});

		Route::group(['prefix' => 'sales'], function () {
			Route::get('/', 'Admin\SalesController@index')->name('admin-sales-list');
			Route::get('/incompletes', 'Admin\SalesController@indexincompletes')->name('admin-sales-list-incompletes');
			Route::get('/list-data', 'Admin\SalesController@listData')->name('admin-sales-list-data');
			Route::get('/list-data-incompletes', 'Admin\SalesController@listDataIncompletes')->name('admin-sales-list-data-incompletes');
			Route::get('/change-status/{id}/{status}', 'Admin\SalesController@changeStatus')->name('admin-sales-change-status');
		});

		Route::group(['prefix' => 'withdraw'], function () {
			Route::get('/', 'Admin\WithdrawController@index')->name('admin-withdraw-list');
			Route::get('/list-data', 'Admin\WithdrawController@listData')->name('admin-withdraw-list-data');
			Route::get('/change-status/{id}/{status}', 'Admin\WithdrawController@changeStatus')->name('admin-withdraw-change-status');
		});

		Route::group(['prefix' => 'direct-message'], function () {
			Route::get('/', 'Admin\DirectMessageController@index')->name('admin-direct-message-list');
			Route::get('/list-data', 'Admin\DirectMessageController@listData')->name('admin-direct-message-list-data');
			Route::get('/direct-message-details', 'Admin\DirectMessageController@details')->name('admin-direct-message-details');
			Route::get('/direct-message-details-list', 'Admin\DirectMessageController@messageList')->name('admin-direct-message-details-list');
			Route::get('/direct-message-details-send', 'Admin\DirectMessageController@messageSend')->name('admin-direct-message-details-send');
			Route::get('/send-bulk-message', 'Admin\DirectMessageController@bulkMessage')->name('admin-send-bulk-message');
			Route::post('/send-bulk-message', 'Admin\DirectMessageController@bulkMessageAction')->name('admin-send-bulk-message-action');


			// Route::get('/change-status/{id}/{status}', 'Admin\DirectMessageController@changeStatus')->name('admin-direct-message-change-status');
		});
		Route::group(['prefix' => 'contactus'], function () {
			Route::get('/', 'ADMIN\ContactusController@index')->name('admin-contact-us');
		});
		Route::group(['prefix' => 'news'], function () {
			Route::get('/news-details', 'Admin\NewsController@newsDetails')->name('admin-news-details');
			Route::get('/new-news', 'Admin\NewsController@newNews')->name('admin-new-news');
			Route::post('/new-news', 'Admin\NewsController@newNewsAction')->name('admin-new-news-action');
			Route::get('/notification-list', 'Admin\NewsController@index')->name('admin-notification-list');
			Route::get('/notification-list-data', 'Admin\NewsController@listData')->name('admin-notification-list-data');
		});
		Route::group(['prefix' => 'seller'], function () {
			Route::get('/', 'Admin\SellerController@index')->name('admin-seller-list');
			Route::get('/list-data', 'Admin\SellerController@listData')->name('admin-seller-list-data');
			Route::get('/change-status/{id}/{status}', 'Admin\SellerController@changeStatus')->name('admin-seller-change-status');
			Route::get('/seller-details', 'Admin\SellerController@SellerDetails')->name('admin-seller-details');
			Route::get('/sold-product-list-data', 'Admin\SellerController@SoldProductListData')->name('admin-sold-product-list-data');
			Route::get('/purchase-list-data', 'Admin\SellerController@purchaseListData')->name('admin-seller-purchase-list-data');
		});

		Route::group(['prefix' => 'affiliator'], function () {
			Route::get('/', 'Admin\AffiliatorController@index')->name('admin-affiliator-list');
			Route::get('/list-data', 'Admin\AffiliatorController@listData')->name('admin-affiliator-list-data');
			Route::get('/change-status/{id}/{status}', 'Admin\AffiliatorController@changeStatus')->name('admin-affiliator-change-status');
			Route::get('/affiliator-details', 'Admin\AffiliatorController@affiliatorDetails')->name('admin-affiliator-details');
			Route::get('/affiliated-product-list-data', 'Admin\AffiliatorController@affiliatedProductListData')->name('admin-affiliated-product-list-data');
			Route::get('/purchase-list-data', 'Admin\AffiliatorController@purchaseListData')->name('admin-affiliator-purchase-list-data');
			Route::get('/affiliator-earning-list', 'Admin\AffiliatorController@affiliatorEarningList')->name('admin-affiliator-earning-list');
			Route::get('/affiliator-earning-list-monthly', 'Admin\AffiliatorController@affiliatorEarningListMonthly')->name('admin-affiliator-earning-list-monthly');
			Route::get('/affiliator-earning-list-data', 'Admin\AffiliatorController@affiliatorEarningListData')->name('admin-affiliator-earning-list-data');
			Route::get('/affiliator-earning-list-data-monthly', 'Admin\AffiliatorController@affiliatorEarningListDataMonthly')->name('admin-affiliator-earning-list-data-monthly');
			Route::get('/affiliator-send-link', 'Admin\AffiliatorController@sendLink')->name('admin-affiliator-approve');

		});

		Route::group(['prefix' => 'buyer'], function () {
			Route::get('/', 'Admin\BuyerController@index')->name('admin-buyer-list');
			Route::get('/list-data', 'Admin\BuyerController@listData')->name('admin-buyer-list-data');
			Route::get('/change-status/{id}/{status}', 'Admin\BuyerController@changeStatus')->name('admin-buyer-change-status');

			Route::get('/buyer-details', 'Admin\BuyerController@buyerDetails')->name('admin-buyer-details');
			Route::get('/purchase-list-data', 'Admin\BuyerController@purchaseListData')->name('admin-buyer-purchase-list-data');
		});


		Route::group(['prefix' => 'lottery'], function () {
			Route::get('/', 'Admin\LotteryController@index')->name('admin-lottery-list');
			Route::get('/list-data', 'Admin\LotteryController@listData')->name('admin-lottery-list-data');
			Route::post('/add-winners', 'Admin\LotteryController@reward')->name('give-lottery-prize');
		});
	});
});
