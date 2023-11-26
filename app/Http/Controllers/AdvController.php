<?php

namespace App\Http\Controllers;

use App\Models\Adv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adv = Adv::all();

        return response()->json($adv);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adv_title' => 'required|string',
            'adv_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'adv_link' => 'nullable|string',
            'adv_duration_seconds' => 'required|integer|min:1',
            'adv_loop_seconds' => 'required|integer|min:1',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $adv_file = $request->file('adv_file');

        if ($adv_file) {
            $adv_file->storeAs('public/adv_file', $adv_file->hashName());
        } else {
            return response()->json(['message' => 'The adv_file field is required.'], 422);
        }

        $adv = Adv::create([
            'adv_title' => $request->input('adv_title'),
            'adv_file' => $adv_file->hashName(),
            'adv_link' => $request->input('adv_link'),
            'adv_duration_seconds' => $request->input('adv_duration_seconds'),
            'adv_loop_seconds' => $request->input('adv_loop_seconds'),
        ]);

        return response()->json(['message'=>'data berhasil di buat','data'=>$adv],201);
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy($id)
    {
        $adv = Adv::findOrFail($id);
        $adv->delete();

        return response()->json(['message'=> 'Delete Berhasil'],200);
    }
}
