<?php
/**
 * Author: Jeanmarc Duong
 * Date: 10/22/2020
 * File: Menu.php
 * Description:
 */

namespace Bakery\Models;

use \Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";
    protected $primaryKey= 'id';
    public $timestamps = false;

    public function orders() {
        return $this->hasMany(Orders::class, 'itemId');
    }

    public static function getMenu($request)
    {

        //get the total number of menu items
        $count = self::count();

        //get query string variables from url
        $params = $request->getQueryParams();

        //Do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; // items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; // offset of the first item

        //Get search terms
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $menu = self::searchMenu($term);
            return $menu;
        } else {
            //Pagination
            $links = self::getLinks($request, $limit, $offset);

            // Sorting.
            $sort_key_array = self::getSortKeys($request);

            $query = Menu::with('orders');
            //$query = Menu::all();
            $query = $query->skip($offset)->take($limit);  // limit the rows

            // sort the output by one or more columns
            foreach ($sort_key_array as $column => $direction) {
                $query->orderBy($column, $direction);
            }

            $menu = $query->get();

            //construct data for the response
            $results = [
                'totalCount' => $count,
                'limit' => $limit,
                'offset' => $offset,
                'links' => $links,
                'sort' => $sort_key_array,
                'data' => $menu
            ];
            return $results;
        }
    }


    //get a menu by menu id
    public static function getMenuById($id)
    {
        $menu = self::findOrFail($id);
        return $menu;
    }

    //get the orders of a menu item
    public static function getMenuOrder($id)
    {
        $menu = self::findOrFail($id)->orders;
        return $menu;
    }

    //create a menu
    public static function createMenu($request)
    {
        $params = $request->getParsedBody();

        //Create new menu object
        $menu = new Menu();

        foreach ($params as $field => $value) {
            $menu->$field = $value;
        }

        $menu->save();
        return $menu;
    }


    //update a menu
    public static function updateMenu($request)
    {
        $params = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $menu = self::findOrFail($id);

        foreach ($params as $field => $value) {
            $menu->$field = $value;
        }
        $menu->save();
        return $menu;
    }


    //delete a menu
    public static function deleteMenu($request)
    {
        $id = $request->getAttribute('id');
        $menu = self::findOrFail($id);
        return($menu->delete());
    }


    public static function getLinks($request, $limit, $offset)
    {
        $count = self::count();
// Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();
// Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . $path . "?
limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . $path . "?
limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . $path . "?
limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . $path . "?
limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . $path . "?
limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];
        return $links;
    }

    public static function getSortKeys($request)
    {
        $sort_key_array = array();

// Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {

                    list($column, $direction) = explode(':', $sort_key);
                }

                $sort_key_array[$column] = $direction;
            }

        }
        return $sort_key_array;
    }

    public static function searchMenu($terms){
        if (is_numeric($terms)) {
            $query = self::where('id', "like", "%$terms%");
        } else {
            $query = self::where('name', 'like', "%$terms%")->orWhere('origin', 'like', "%$terms%");
        }
        $results=$query->get();
        return$results;
    }


}