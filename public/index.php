<?php

echo '<pre>';
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$urlComponents = array_values(array_filter(explode('/', $url)));
$urlComponentsCount = count($urlComponents);

echo 'Requested Url: ' . $url . '<br>';

$configuredRoutes = array(
	array(
		'name' => 'home',
		'url' => '/',
		'method' => 'GET',
		'controller' => 'home',
		'action' => 'index'
	),
	array(
		'name' => 'get_comment',
		'url' => '/comments/{:id}',
		'method' => 'GET',
		'controller' => 'comment',
		'action' => 'show'
	),
	array(
		'name' => 'get_comments',
		'url' => '/comments',
		'method' => 'GET',
		'controller' => 'comment',
		'action' => 'index'
	),
	array(
		'name' => 'update_comment',
		'url' => '/comments/{:id}',
		'method' => 'PUT',
		'controller' => 'comment',
		'action' => 'update'
	),
	array(
		'name' => 'delete_comment',
		'url' => '/comments/{:id}',
		'method' => 'DELETE',
		'controller' => 'comment',
		'action' => 'delete'
	),
	array(
		'name' => 'create_comment',
		'url' => '/comments',
		'method' => 'POST',
		'controller' => 'comment',
		'action' => 'create'
	),

	array(
		'name' => 'get_users',
		'url' => '/users/{:id}',
		'method' => 'GET',
		'controller' => 'users',
		'action' => 'show'
	),
	array(
		'name' => 'get_about',
		'url' => '/page/about',
		'method' => 'GET',
		'controller' => 'page',
		'action' => 'about'
	),
	array(
		'name' => 'get_contact',
		'url' => '/page/contact',
		'method' => 'GET',
		'controller' => 'page',
		'action' => 'contact'
	),
	array(
		'name' => 'get_terms',
		'url' => '/terms-of-service',
		'method' => 'GET',
		'controller' => 'page',
		'action' => 'terms'
	),
	array(
		'name' => 'get_profile',
		'url' => '/{:user_name}',
		'method' => 'GET',
		'controller' => 'user',
		'action' => 'profile'
	),
);


$foundRoute = null;

foreach ($configuredRoutes as $route)
{
	$urlComponentsFromConfiguration = array_values(array_filter(explode('/', $route['url'])));

	$matchCount = routeMatchCount($urlComponents, $urlComponentsFromConfiguration, $route, $method);

	if (wasRouteFound($matchCount, $urlComponentsCount))
	{
		echo '<br>Found it!<br>==============<br>';
		$foundRoute = $route;
		break;
	}
}

function routeMatchCount($urlComponents, $urlComponentsFromConfiguration, $route, $method)
{
	$matchCount = -1;
	$urlComponentsCount = count($urlComponents);
	$urlComponentsFromConfigurationCount = count($urlComponentsFromConfiguration);

	if ($urlComponentsCount === $urlComponentsFromConfigurationCount && $route['method'] === $method)
	{
		$matchCount = calculateCount($urlComponents, $urlComponentsFromConfiguration);
	}
	return $matchCount;
}

function calculateCount($urlComponents, $urlComponentsFromConfiguration)
{
	$matchCount = 0;
	$urlComponentsCount = count($urlComponents);
	for ($i = 0; $i < $urlComponentsCount; $i++)
	{
		if (false !== strpos($urlComponentsFromConfiguration[$i], '{:'))
		{
			// Get the argument, and do something with it.
			++$matchCount;
		} else if ($urlComponents[$i] === $urlComponentsFromConfiguration[$i]) {
			++$matchCount;
		}
	}
	return $matchCount;
}

function wasRouteFound($matchCount, $urlComponentsCount)
{
	if ($matchCount === $urlComponentsCount)
	{
		return true;
	}
	return false;
}

if (!is_array($foundRoute))
{
	// Throw exception.
	echo '<br>' . $url . ' Method ' . $method . ' not found in the configured routes<br>';
	return;
}
print_r($foundRoute);
echo '</pre>';