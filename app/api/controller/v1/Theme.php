<?php


namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeDetailsMissException;
use app\lib\exception\ThemeMissException;

class Theme
{
    /**
     * 获取主题列表
     * @url theme?ids=id,id2,...
     * @param $ids
     * @return \think\response\Json
     * @throws ThemeMissException
     */

    public function getSimpleList($ids): \think\response\Json
    {
        (new IDCollection())->goCheck();

        $theme = ThemeModel::getThemeByIDs($ids);
        if ($theme->isEmpty()) {
            throw new ThemeMissException();
        }
        $theme = $theme->toArray();
        return json($theme, 200);
    }

    /**
     * 获取主题商品信息
     * @url theme/id
     * @param $id
     * @return \think\response\Json
     * @throws ThemeDetailsMissException
     */
    public function getComplexOne($id): \think\response\Json
    {
        (new IDMustBePostiveInt())->goCheck();

        $themeDetails = ThemeModel::getThemeWithProductByID($id);

        if (!$themeDetails) {
            throw new ThemeDetailsMissException();
        }

        return json($themeDetails, 200);
    }

}