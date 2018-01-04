<?php

namespace App\Http\Controllers;

use App\Submenu;
use App\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubmenuController extends Controller
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
        $this->validate($request,[
          'menu_id' => 'required',
          'name'    => 'required',
        ]);
        $submenu = Submenu::create([
          'menu_id' =>$request->menu_id,
          'name'    =>$request->name,
        ]);
        return $submenu;
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
        $submenu = DB::table('submenus')
            ->join('menus', 'submenus.menu_id', '=', 'menus.id')
            ->select('submenus.*', 'menus.name as keyname')
            ->paginate(10);
        return $submenu;
    }

    public function getmenu()
    {
      # code...
      $menu = DB::table('menus')->select('id as idkey', 'name as namekey')->get();
      return $menu;
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
        return Submenu::find($id);
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
        $submenu = Submenu::findOrFail($id);
        $submenu->menu_id = $request->menu_id;
        $submenu->name    = $request->name;
        $submenu->save();
        return response()->json($submenu, 200);
    }

    public function search(Request $request)
    {
      # code...
      $submenu = Submenu::where('name', 'LIKE',"%$request->q%")->get();
      return $submenu;
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
        $submenu = Submenu::destroy($id);
        return $submenu;
    }
}
