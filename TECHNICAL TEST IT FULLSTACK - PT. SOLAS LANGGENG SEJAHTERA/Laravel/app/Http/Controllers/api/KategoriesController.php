<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class KategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtAuth', ['except' => ['login','register']]);
    }
    public function show() {
        return response()->json([
            'error' => false,
            'massage' => Kategori::getAll()
        ], 200);
    }

    public function showbyid($id) {
        $id = Kategori::getAll($id);
        if ($id) {
            return response()->json([
                'error'     => false,
                'massage'   => $id], 200);
        }
        return response()->json([
            'error'     => true,
            'massage'   => 'Data not found'], 404);
    }

    public function post() {
        $validator = Validator::make(request()->all(), [
            'nama'      => 'required|unique:kategoris',
        ], [
            'nama.required'     => 'Kategori wajib diisi',
            'nama.unique'       => 'Kategori sudah ada',
        ]);

        if (!$validator->fails()) {
            Kategori::create(request()->except('_token'));
            return response()->json([
                'error' => false,
                'message' => "Data created successfully",
                'data' => request()->all()
            ], 201);
        }
        return response()->json(['error' => true, 'message' => $validator->messages()], 500);
    }

    public function put($id) {
        $validator = Validator::make(request()->all(), [
            'nama'      => 'required|unique:kategoris',
        ], [
            'nama.required'     => 'Kategori wajib diisi',
            'nama.unique'       => 'Kategori sudah ada',
        ]);

        $id = Kategori::find($id);
        if ($id == null) {
            return response()->json([
                'error' => true,
                'message' => "Data not found",
            ], 404);
        }

        if (!$validator->fails()) {
            $id->update(request()->except('_token'));
            return response()->json([
                'error' => false,
                'message' => "Data updated successfully",
                'data' => request()->all()
            ], 200);
        }
        return response()->json(['error' => true, 'message' => $validator->messages()], 500);
    }

    public function delete($id) {
        $id = Kategori::find($id);
        if ($id) {
            $id->delete();
            return response()->json([
                'error' => false,
                'message' => "Data deleted successfully",
            ], 200);
        }

        return response()->json([
            'error' => true,
            'message' => "Data not found",
        ], 404);
    }
}
