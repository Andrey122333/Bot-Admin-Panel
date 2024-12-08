<?php
namespace App\Http\Livewire;

use App\Models\GeoPositions;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeoPositionBlade extends Component
{
    public $locations;
    public $selectedLocation;
    public $name;
    public $latitude;
    public $longitude;
    public $routeDescription;
    public $mapLink;

    private function getHttpClient()
    {
        return Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Upgrade-Insecure-Requests' => '1',
        ])
            ->withOptions([
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => true,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true,
                ],
            ]);
    }

    public function updatedSelectedLocation($value)
    {
        $this->name = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->routeDescription = null;

        $selectedLocationData = GeoPositions::where('name', $value)->first();
        if ($selectedLocationData) {
            $this->name = $selectedLocationData->name;
            $this->latitude = $selectedLocationData->latitude;
            $this->longitude = $selectedLocationData->longitude;
            $this->routeDescription = $selectedLocationData->route_description;
        }
    }

    public function getCoordinates()
    {
        if (!$this->mapLink) {
            return;
        }

        $response = $this->getHttpClient()->get($this->mapLink);


        $redirectedUrl = $response->effectiveUri()->__toString();


        preg_match_all('/(\d+\.\d+)/', $redirectedUrl, $matches);

        if (count($matches[0])) {
            $this->latitude = $matches[0][0];
            $this->longitude = $matches[0][1];

            $this->mapLink = null;
        }
    }

    public function saveLocation()
    {
        $this->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'routeDescription' => 'required',
        ]);

        GeoPositions::updateOrCreate(
            ['name' => $this->name],
            [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'route_description' => $this->routeDescription,
            ]
        );

        $this->getLastLocation();

        $this->reset(['name', 'latitude', 'longitude', 'routeDescription']);
    }

    public function deleteLocation()
    {
        if ($this->selectedLocation) {
            GeoPositions::where('name', $this->selectedLocation)->delete();
            $this->selectedLocation = null;
            $this->getLastLocation();
        }
    }

    public function getLastLocation()
    {
        $lastLocation = GeoPositions::latest('updated_at')->first();
        $this->selectedLocation = $lastLocation->name ?? null;
        $this->name = $lastLocation->name ?? null;
        $this->latitude = $lastLocation->latitude ?? null;
        $this->longitude = $lastLocation->longitude ?? null;
        $this->routeDescription = $lastLocation->route_description ?? null;
    }

    public function render()
    {
        $this->locations = GeoPositions::pluck('name');
        if ($this->selectedLocation && !$this->name && !$this->latitude && !$this->longitude && !$this->routeDescription) {
            $selectedLocationData = GeoPositions::where('name', $this->selectedLocation)->first();
            $this->name = $selectedLocationData->name ?? null;
            $this->latitude = $selectedLocationData->latitude ?? null;
            $this->longitude = $selectedLocationData->longitude ?? null;
            $this->routeDescription = $selectedLocationData->route_description ?? null;
        }
        return view('livewire.geo-position-blade');
    }
}
