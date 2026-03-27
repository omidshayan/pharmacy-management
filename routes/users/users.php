<?php
require_once 'Http/Controllers/users/User.php';

// add product routes
uri('users', 'App\User', 'showUsers');
uri('add-user', 'App\User', 'addUser');
uri('user-store', 'App\User', 'userStore', 'POST');
uri('user-details/{id}', 'App\User', 'userDetails');
uri('edit-user/{id}', 'App\User', 'editUser');
uri('edit-user-store/{id}', 'App\User', 'editUserStore', 'POST');

// live search
uri('search-user-details', 'App\User', 'searchUserDetails', 'POST');


// live search items
uri('search-itemm', 'App\User', 'searchItem', 'POST');

