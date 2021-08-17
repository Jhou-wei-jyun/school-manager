<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\API\ApiHelper;
use App\Option;
use App\OptionType;
use App\User;

class OptionController extends Controller
{
    use ApiHelper;

    public function indexOption(Request $request)
    {
        $type = (string)$request->type;
        $user_id = (int)$request->user_id;
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $department_id = User::find($user_id)->department_id;
        $option_types = OptionType::where('type', 'App\\' . $type)
            ->with(['options'=> function($query) use($department_id){
                $query->where('department_id', $department_id);
            }])->get();
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
                'type' => $option->optionType->value,
            ]);
            return $collection;
        });
        return $options;
    }
}
