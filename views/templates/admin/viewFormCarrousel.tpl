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

{foreach $data as $item}
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Type de la vidéo</th>
        <th scope="col">URL</th>
        <th scope="col">Height</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{escape:'html_all':'UTF-8':$item.id_video_type}</th>
        <td>{escape:'html_all':'UTF-8':$item.videoType}</td>
        <td>{escape:'html_all':'UTF-8':$item.url}</td>
        <td>{escape:'html_all':'UTF-8':$item.height}</td>
      </tr>
    </tbody>
  </table>
  {/foreach}

