<?php

namespace App\Http\Controllers;

use App\Halaman;
use Illuminate\Http\Request;

class HalamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
          'judul'     => 'required',
          'deskripsi' => 'required',
        ]);
        $halaman = Halaman::create([
          'judul'     =>$request->judul,
          'slug'      =>str_slug($request->judul),
          'deskripsi' =>$request->deskripsi,
          'meta'      =>$request->meta,
        ]);

        return $halaman;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        return Halaman::paginate(10);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return Halaman::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $halaman = Halaman::findOrFail($id);
        $halaman->judul     =$request->judul;
        $halaman->slug      =str_slug($request->judul);
        $halaman->deskripsi =$request->deskripsi;
        $halaman->meta      =$request->meta;
        $halaman->save();

        return response()->json($halaman, 200);
    }

    public function search(Request $request)
    {
      # code...
      $halaman = Halaman::where('judul', 'LIKE', "%$request->q%")->get();

      return $halaman;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $halaman = Halaman::destroy($id);

    }
}
