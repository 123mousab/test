<?php


namespace App\Services;


use App\Http\Resources\NewMenuResource;
use App\Models\Menu;
use App\Models\MenuDetail;
use App\Models\MenuGroupDetail;
use App\Models\MenuIngredientDetail;

class NewMenuService extends BaseService
{

    static protected $model = Menu::class;
    static protected $resource = NewMenuResource::class;

    public function save($data)
    {
        $menu = $this->create($data);

        $menuFirstGroup = collect(request('first_group'));

        $menuSecondGroup = collect(request('second_group'));

        $menu->menuIngredientDetails()->createMany($menuFirstGroup);
        return $menu->menuGroupDetails()->createMany($menuSecondGroup);
    }

    public function update($menu_id, $data)
    {
        MenuIngredientDetail::query()->where('menu_id', $menu_id)->delete();
        MenuGroupDetail::query()->where('menu_id', $menu_id)->delete();

       Menu::query()->where('id', $menu_id)->update([
          'cooking_date' => $data['cooking_date']
       ]);

        $menu = Menu::query()->find($menu_id);

        $menuFirstGroup = collect(request('first_group'))->reverse();

        $menuSecondGroup = collect(request('second_group'))->reverse();

        $menu->menuIngredientDetails()->createMany($menuFirstGroup);
        return $menu->menuGroupDetails()->createMany($menuSecondGroup);
    }
}
