<?php

namespace App\Http\Controllers;

use App\Interfaces\CountryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Traits\HttpResponses;

class CountryController extends Controller
{
    use HttpResponses;
    protected mixed $countryRepository;

    public function __construct(CountryRepositoryInterface $pattern)
    {
        $this->countryRepository = $pattern;
    }

    public function getPricePerKmByIp(Request $request)
    {
        $ip = $request->ip();
        $countryCode = 'IQ';

        if ($ip && $ip != '127.0.0.1' && $ip != '::1') {
            $response = Http::get("http://ipinfo.io/{$ip}/json");

            if (!$response->successful() || !isset($response['country'])) {
                return $this->error(null, 'تعذر تحديد الدولة');
            }

            $countryCode = $response['country'];
        }

        $country = $this->countryRepository->getByCountryCode($countryCode);

        if (!$country) {
            return $this->error(null, 'الدولة غير مدعومة');
        }

        return $this->success([
            'country' => $country->name,
            'country_code' => $country->country_code,
            'price_per_km' => $country->price_per_km,
        ]);
    }
}