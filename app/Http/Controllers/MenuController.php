<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MenuController extends Controller
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
              'name'  => 'required',
              'order' => 'required',
        ]);
        $menu = Menu::create([
          'name'   =>$request->name,
          'order'  =>$request->order,
          'link'   =>$request->link
        ]);
        return $menu;
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
//        $menu = Menu::paginate(10)->orderBy('order', 'desc');
        $menu = DB::table('menus')
                    ->orderBy('order','desc')
                    ->paginate(10);
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
        return Menu::find($id);
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
        $menu = Menu::findOrFail($id);
        $menu->name   =$request->name;
        $menu->order  =$request->order;
        $menu->link   =$request->link;
        $menu->save();

         return response()->json($menu, 200);
        //
        // $menu = Menu::find($id)->update([
        //   'name'    =>$request->name,
        //   'order'   =>$request->order,
        //   'link'    =>$request->link
        // ]);
        // if ($menu == true){
        //   return response(200);
        // }
        // else {
        //   # code...
        //   return response(500);
        // }
    }

    public function search(Request $request)
    {
      # code...
      $menu = Menu::where('name', 'LIKE',"%$request->q%")->get();
      return $menu;
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
        $menu = Menu::destroy($id);
        return $menu;
    }
}
