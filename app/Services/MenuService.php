<?php


namespace App\Services;


use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\MenuDetail;

class MenuService extends BaseService
{

    static protected $model = Menu::class;
    static protected $resource = MenuResource::class;

    public function save($data)
    {
        $menu = $this->create($data);

        $menuFirstGroup = collect(request('first_group'));

        $menuSecondGroup = collect(request('second_group'));

        $menu->menuDetails()->createMany($menuFirstGroup);
        return $menu->menuDetails()->createMany($menuSecondGroup);
    }

    public function update($menu_id, $data)
    {
        MenuDetail::query()->where('menu_id', $menu_id)->delete();

       Menu::query()->where('id', $menu_id)->update([
          'cooking_date' => $data['cooking_date']
       ]);

        $menu = Menu::query()->find($menu_id);

        $menuFirstGroup = collect(request('first_group'));

        $menuSecondGroup = collect(request('second_group'));

        $menu->menuDetails()->createMany($menuFirstGroup);
        return $menu->menuDetails()->createMany($menuSecondGroup);
    }
}
