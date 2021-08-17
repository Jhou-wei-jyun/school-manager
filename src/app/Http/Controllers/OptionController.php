<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\API\ApiHelper;
use App\Option;
use App\OptionType;

class OptionController extends Controller
{
    use ApiHelper;

    public function indexOptionType(Request $request)
    {
        $optionTypes = OptionType::all();
        return $optionTypes;
    }
    public function indexOption(Request $request)
    {
        $type = (string)$request->type;
        $department_id = (int)$request->department_id;
        $validator = Validator::make(
            [
                'type' => $type,
                'department_id' => $department_id,
            ],
            [
                'type' => 'required',
                'department_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $option_types = OptionType::where('type', 'App\\' . $type)
                ->with(['options'=> function($query) use($department_id){
                    $query->where('department_id', $department_id);
                }])->get();
        // $options = Option::where('department_id', $department_id)
        // ->with(['optionType'=> function($query) use($type){
        //     $query->where('type', 'App\\' . $type);
        // }])->get();
        if ($option_types->pluck('options')->collapse()->isEmpty()) {

            $data = [];
            foreach ($option_types as $option_type) {
                $new = [
                    'options' => $option_type->default,
                    'option_type_id' => $option_type->option_type_id,
                    'department_id' => $department_id,
                ];
                $data[] = $new;
            }
            tap(Option::insert($data));
            $option_types = OptionType::where('type', 'App\\' . $type)
                ->with(['options'=> function($query) use($department_id){
                    $query->where('department_id', $department_id);
                }])->get();
        }

        $options = $option_types->pluck('options')->collapse();
        $options = $options->map(function ($option) {
            $collection = collect([
                'option_id' => $option->option_id,
                'options' => json_decode($option->options),
                'type_id' => $option->optionType->option_type_id,
                'type' => $option->optionType->value == 'return' ? "Return" : $option->optionType->value,
            ]);
            return $collection;
        });
        return $options;
    }
    public function editOption(Request $request)
    {
        $option_id = (int)$request->option_id;
        $options = (array)$request->options;
        $validator = Validator::make(
            [
                'option_id' => $option_id,
            ],
            [
                'option_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $option = tap(Option::find($option_id))
            ->update([
                'options' => json_encode($options),
            ]);
        return $this->succeed(json_decode($option->options), 200);
    }
}
