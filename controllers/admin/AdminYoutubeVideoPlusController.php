<?php
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
require_once _PS_MODULE_DIR_ . 'youtubevideoplus/classes/YoutubeVideoPlusClass.php';

class AdminYoutubeVideoPlusController extends ModuleAdminController
{
    public function __construct()
    {
        /* Table me permet de recupèrer le nom de ma table dans le tableau definition */
        $this->table = YoutubeVideoPlusClass::$definition['table'];
        /* j'appel la class elle même(YoutubeVideoPlusClass) */
        $this->className = YoutubeVideoPlusClass::class;
        /* nom du module
         * ? pourquoi ne pas le faire dynamiquement?
         * parce que le nom du module ne doit pas changer*/
        $this->module = Module::getInstanceByName('youtubevideopluscarousel');
        /* Clè primaire */
        $this->identifier = YoutubeVideoPlusClass::$definition['primary'];
        /* pour le classement evite des erreur */
        $this->_orderBy = YoutubeVideoPlusClass::$definition['primary'];
        /* j'active bootstrap */
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_video' => [
                'title' => 'ID',
                'search' => true,
            ],
            'videoType' => [
                'title' => 'Type de la vidéo',
                'search' => true,
            ],
            'url' => [
                'title' => 'URL',
                'search' => true,
            ],
        ];

        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function renderForm()
    {
        $options = [
            [
                'id_option' => 1,
                'name' => 'youtube video',
            ],
            [
                'id_option' => 2,
                'name' => 'dailymotion',
            ],
        ];

        $this->fields_form = [
            'legend' => [
                'title' => 'Ajout de vidéos pour le carrousel',
                'icon' => 'icon-cog',
            ],
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->l('Type de la vidéo:'),

                    'name' => 'videoType',
                    'required' => true,
                    'id' => 'videoType',
                    'options' => [
                        'query' => $options,
                        'id' => 'id_option',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('L\'url de la vidéo'),
                    'name' => 'url',
                    'id' => 'urlVideo',
                    'size' => 225,
                    'require' => true,
                ],
            ],
            'submit' => [
                'title' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ];

        return parent::renderForm();
    }

    public function renderView()
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from($this->table)
            ->where('id_video_type=' . Tools::getValue('id_video_type'));
        $data = Db::getInstance()->executes($sql);

        $tplFile = _PS_MODULE_DIR_ . 'youtubevideoplus/views/templates/admin/viewFormCarrousel.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign([
            'data' => $data,
        ]);

        return $tpl->fetch();
    }
}
