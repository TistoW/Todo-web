<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use App\Http\Response;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller {

    use Response;
    use Helper;

    public function index(Request $request) {
        $search = $request->query('search');
        $userId = $this->getUserId($request);

        $mData = Todo::orderBy("id", "desc")
            ->where('userId', $userId)
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                }
            })->get();
        return $this->success($mData);
    }

    public function create() {

    }

    public function store(Request $request) {
        $validasi = Validator::make($request->all(), [

        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $userId = $this->getUserId($request);
        $mData = Todo::create(array_merge($request->all(), [
            'userId' => $userId,
            'isActive' => true
        ]));

        if ($mData) {
            return $this->success($mData);
        }
        return $this->error('Gagal membuat data');
    }

    public function show($id) {
        $mData = Todo::whereId($id)->first();
        if ($mData) {
            return $this->success($mData);
        }
        return $this->error('Data tidak di temukan');
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        $mData = Todo::where('id', $id)->first();
        if ($mData) {
            $mData->update($request->all());
            return $this->success($mData);
        }
        return $this->error('Gagal membuat data');
    }

    public function destroy($id) {
        $mData = Todo::where('id', $id)->first();
        if ($mData) {
            $mData->delete();
            return $this->success($mData);
        }
        return $this->error('Gagal membuat data');
    }
}
