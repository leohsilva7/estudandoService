<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Temperature;
use App\Services\TemperatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

#[AllowDynamicProperties]
class TemperatureController extends Controller
{
    public function __construct(TemperatureService $temperatureConversion)
    {
        $this->conversionService = $temperatureConversion;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $temperatures = Temperature::select('city', 'temperature_fahrenheit')->get();
        $temperatureToC = $temperatures->map(function ($item){
           return [
               'city' => $item->city,
               'temperature_fahrenheit' => $item->temperature_fahrenheit,
               'temperature_c' => $this->conversionService->toCelsius(floatval($item->temperature_fahrenheit)),
           ];
        });
        return response()->json([
            'message' => 'Retornando todas as temperaturas!',
            'data' => $temperatureToC,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:255',
            'temperature_fahrenheit' => 'required|string|max:10'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => 'Erro ao adicionar temperatura no sistema!'
            ],422);
        }

        try {
            $newTemperature = Temperature::create([
                'city' => $request->city,
                'temperature_fahrenheit' => $request->temperature_fahrenheit,
            ]);
            return response()->json([
                'message' => 'Temperatura adicionada com sucesso!',
                'temperature' => $newTemperature
            ],201);
        }
        catch (\Exception $ex){
            return response()->json([
                'error' => 'Erro ao criar o registro no db!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Temperature $temperature)
    {
        $data = [
                'city' => $temperature->city,
                'temperature_fahrenheit' => $temperature->temperature_fahrenheit,
                'temperature_c' => $this->conversionService->toCelsius(floatval($temperature->temperature_fahrenheit)),
        ];
        return response()->json([
            'message' => 'Temperatura Encontrada!',
            'data' => $data,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Temperature $temperature)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|max:255|string',
            'temperature_fahrenheit' => 'required|string|max:10'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => 'Erro ao validar dados!',
                'messages' => $validator->errors(),
            ],422);
        }

        $updateTemperature = $temperature->update([
            'city' => $request->city,
            'temperature_fahrenheit' => $request->temperature_fahrenheit,
        ]);

        if (!$updateTemperature){
            return  response()->json([
                'error' => 'Erro ao atualizar o registro de temperatura',
            ],500);
        }
        return response()->json([
            'message' => 'Temperatura Atualizada!',
            'temperature' => $temperature,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Temperature $temperature)
    {
        $deleteTemperature = $temperature->delete();

        if (!$deleteTemperature){
            return response()->json([
                'error' => 'Erro ao deletar Temperatura',
            ],500);
        }
        return response()->json([
            'message' => 'Temperatura Deletada com Sucesso!',
        ],204);
    }
}
