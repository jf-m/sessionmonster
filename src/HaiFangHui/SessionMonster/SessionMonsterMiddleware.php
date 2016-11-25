<?php namespace HaiFangHui\SessionMonster;

use Closure;

class SessionMonsterMiddleware {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		$response = $next($request);
		$session = \Session::all();

		unset($session['_token']);
		unset($session['_previous']);

		$empty_flash = true;
		if (isset($session['_flash'])) {
			if (is_array($session['_flash']['old']) && (count($session['_flash']['old']) > 0)) {
				$empty_flash = false;
			}

			if (is_array($session['_flash']['new']) && (count($session['_flash']['new']) > 0)) {
				$empty_flash = false;
			}
		}

		if ($empty_flash) {
			unset($session['_flash']);
		}

		if (count($session) >= 1) {
			return $response;
		}

		$response->headers->set('X-No-Session', 'yeah');
		return $response;
	}

}
