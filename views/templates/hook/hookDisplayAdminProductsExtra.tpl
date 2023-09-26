{* 
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
*}
{* enctype="multipart/form-data" :  a ajouter quand il y a des téléchargement de fichier*}

<form method="POST" action="#" enctype="multipart/form-data">
  <div class="form-group col">
    <label for="videoType" class="col-sm-2 col-form-label">Type vidéo</label>
      <select name="videoType" id="videoType" class="form-select" onchange="toggleInput()" aria-label="Default select example">
        <option selected></option>
        <option value="dailymotion">Dailymotion</option>
        <option value="youtube">Youtube</option>
      </select>
  </div>
  <div class="form-group col" id="urlVideoContainer">
    <label for="urlVideo" class="col-sm-2 col-form-label">Url</label>
    <div class="col-sm-10">
      <input type="url" name="urlVideo" class="form-control" id="urlVideo" placeholder="https://www.youtube.com/watch?v=pJtkyUub7oo&list=RDMM&start_radio=1">
    </div>
  </div>
</form>


