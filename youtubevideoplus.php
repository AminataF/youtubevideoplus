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
/**TEST MODIF OK */
use PrestaShop\PrestaShop\Adapter\Entity\Tools;

if (!defined('_PS_VERSION_')) {
    exit;
}

class YoutubeVideoPlus extends Module
{
    public function __construct()
    {
        $this->name = 'youtubevideoplus';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Aminata Fofy';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_,
        ];

        parent::__construct();

        $this->displayName = $this->l('Youtube vidéo +');
        $this->description = $this->l('Ajout de vidéo youtube');
        $this->confirmUninstall = $this->l('Êtes vous sur de vouloir supprimer ce module ?');
    }

    public function install()
    {
        if (!parent::install()
        || !Configuration::updateValue('URL_VIDEO', '')
        || !Configuration::updateValue('PATH_VIDEO', '')
        || !Configuration::updateValue('HEIGHT_VIDEO', '')
        || !Configuration::updateValue('TYPE_VIDEO', '')
        || !Configuration::updateValue('UPLOAD_VIDEO', '')
        || !$this->registerHook('displayHeader')
        || !$this->registerHook('displayContentWrapperTop')
        || !$this->registerHook('displayReassurance')
        || !$this->registerHook('displayLeftColumn') || !$this->registerHook('displayProductAdditionalInfo')
        || !$this->installTab('AdminYoutubeVideoPlus', 'Ajout de vidéos pour le carrousel', 'AdminCatalog')
        || !$this->createTable()
        || !$this->registerHook('displayAdminProductsExtra')
        || !$this->installAlterTab()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
        || !$this->uninstallTab()
        || !$this->deleteTable()
        || !$this->deleteAlterTab()
        || !Configuration::deleteByName('URL_VIDEO', '')
        || !Configuration::deleteByName('PATH_VIDEO', '')
        || !Configuration::deleteByName('HEIGHT_VIDEO', '')
        || !Configuration::deleteByName('TYPE_VIDEO', '')
        || !Configuration::deleteByName('UPLOAD_VIDEO', '')) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        return $this->postProcess() . $this->renderForm();
    }

    public function createTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'youtubevideopluscarousel(
            id_video int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            videoType VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL)');
    }

    /* Alter table for add typevideo ... in the product Table */
    public function installAlterTab()
    {
        return Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product 
            ADD videoType VARCHAR(255) NULL ,
            ADD urlVideo VARCHAR(255) NULL');
    }

    /* Delete the ps_youtubevideoplus table */
    public function deleteTable()
    {
        return Db::getInstance()->execute('DROP TABLE ' . _DB_PREFIX_ . 'youtubevideopluscarousel');
    }

    public function deleteAlterTab()
    {
        return Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product
        DROP videoType,
        DROP urlVideo');
    }

    public function installTab($className, $tabName, $tabParentName)
    {
        $tab = new Tab();

        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = [];

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if ($tabParentName) {
            $tab->id_parent = Tab::getIdFromClassName($tabParentName);
        } else {
            $tab->id_parent = 10;
        }

        $tab->module = $this->name;

        return $tab->add();
    }

    /* delete the ongle in the Back-office */
    public function uninstallTab()
    {
        $idTab = Tab::getIdFromClassName('AdminYoutubeVideoPlus');
        $tab = new Tab($idTab);

        return $tab->delete();
    }

    /* création du formulaire de configuration pour la vidéo d'accueil */
    public function renderForm()
    {
        $options = [
            [
                'id_option' => 1,
                'name' => 'youtube video',
            ],
            [
                'id_option' => 2,
                'name' => 'dailymotion video',
            ],
            [
                'id_option' => 3,
                'name' => 'uploader votre video',
            ],
        ];

        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            /* C'est un tableau d'input je peux en mettre beaucoup à la suite */
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->l('Shipping method:'),

                    'name' => 'TYPE_VIDEO',
                    'required' => true,
                    'id' => 'typeVideo',
                    'options' => [
                        'query' => $options,
                        'id' => 'id_option',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => 'Url',
                    'name' => 'URL_VIDEO',
                    'id' => 'urlVideo',
                    'size' => 20,
                    'require' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'height',
                    'name' => 'HEIGHT_VIDEO',
                    'size' => 20,
                    'require' => true,
                ],
                [
                    'type' => 'file',
                    'label' => 'uploader un fichier',
                    'name' => 'UPLOAD_VIDEO',
                    'id' => 'uploadVideo',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('save'),
                'class' => 'btn btn-primary',
                'name' => 'saving',
            ],
            'enctype' => 'multipart/form-data',
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link
            ->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->fields_value['URL_VIDEO'] = Configuration::get('URL_VIDEO');
        $helper->fields_value['HEIGHT_VIDEO'] = Configuration::get('HEIGHT_VIDEO');
        $helper->fields_value['TYPE_VIDEO'] = Configuration::get('TYPE_VIDEO');
        $helper->fields_value['UPLOAD_VIDEO'] = Configuration::get('UPLOAD_VIDEO');

        $output = $helper->generateForm($fieldsForm);

        $output .= <<< HTML
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var videoType = document.getElementById('typeVideo');
                var uploadVideo = document.getElementById('uploadVideo');
                var urlVideo = document.getElementById('urlVideo');
                
                /*séléction des éléments parent/grandparent pour récupérer le label*/
               /* Accédez à l'élément parent*/
                var urlParentElement = urlVideo.parentNode;
                /* Accédez à l'élément grand-parent*/
                var urlGrandParentElement = urlParentElement.parentNode;
                /* Faites quelque chose avec l'élément grand-parent*/
                /* console.log(urlGrandParentElement);*/
        
                /* Accédez à l'élément label du téléchargement */
                var uploadGrandParent = uploadVideo.parentNode.parentNode.parentNode.parentNode;

                /* console.log(uploadGrandParent);*/

                videoType.addEventListener('change', function() {
                    if (videoType.value === '1' || videoType.value === '2') {
                        urlGrandParentElement.style.display = 'block';
                        grandParent3Element.style.display = 'none';
                        /*grandParent3Element.style.display = 'none';*/
                        } else if (videoType.value === '3') {
                            urlGrandParentElement.style.display = 'none';
                            grandParent3Element.style.display = 'block';
                        }
                });
            });
            </script>
        HTML;

        return $output;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('saving')) {
            if (empty(Tools::getValue('TYPE_VIDEO'))) {
                return '<div class="alert alert-danger" role="alert">
                            Vous devez indiquer le type de la vidéo
                        </div>';
            } else {
                Configuration::updateValue('TYPE_VIDEO', Tools::getValue('TYPE_VIDEO'));
            }

            if (!empty(Tools::getValue('HEIGHT_VIDEO')) && (int) Tools::getValue('HEIGHT_VIDEO') > 555 || empty(Tools::getValue('HEIGHT_VIDEO'))) {
                return '<div class="alert alert-danger" role="alert">
                        Erreur la hauteur est trop haute ( hauteur maximum: 555px ) ou vide.
                        </div>';
            } else {
                Configuration::updateValue('HEIGHT_VIDEO', Tools::getValue('HEIGHT_VIDEO'));
            }

            if (!empty(Tools::getValue('UPLOAD_VIDEO'))) {
                $message = $this->importVideosForHome();

                if (empty($message)) {
                    $this->displayError('Erreur lors du téléchargement');
                } else {
                    $this->displayConfirmation('La vidéo a été téléchargée et stockée avec succès.');
                }
            }

            if (!empty(Tools::getValue('URL_VIDEO'))
                && preg_match('#^(http|https)://[w-]+[w.-]+.[a-zA-Z]{2,6}#i', Tools::getValue('URL_VIDEO')) === 0) {
                return '<div class="alert alert-danger" role="alert">
                                Vous n\'avez pas indiquer l\'url de votre vidéo
                            </div>';
            } else {
                Configuration::updateValue('URL_VIDEO', Tools::getValue('URL_VIDEO'));
            }

            return $this->displayConfirmation('Modifications de la vidéo d\'accueil réussi');
        }
    }

    private function importVideosForHome()
    {
        $uploadVideo = $_FILES['UPLOAD_VIDEO'];

        /* verify the type of the video */
        $allowedExtensions = ['mp4', 'mov', 'avi', 'pdf', 'jpg', 'txt'];
        $videoExtension = strtolower(pathinfo($uploadVideo['name'], PATHINFO_EXTENSION));

        if (!in_array($videoExtension, $allowedExtensions)) {
            return 'Le fichier sélectionné n\'est pas une vidéo valide.';
        }

        /* verify if i have a download error */
        if ($uploadVideo['error'] !== UPLOAD_ERR_OK) {
            return 'Erreur lors du téléchargement de la vidéo.';
        }

        /* path of the storage for video */
        $destinationPath = _PS_UPLOAD_DIR_ . $uploadVideo['name'];

        /* move the load file to the destination folder */
        if (!move_uploaded_file($uploadVideo['tmp_name'], $destinationPath)) {
            return 'Erreur lors du téléchargement de la vidéo.';
        }
        Configuration::updateValue('PATH_VIDEO', $destinationPath);

        return 'Votre vidéo a bien été enregistrée.';
    }

    public function hookHeader($params)
    {
        $currentController = Context::getContext()->controller->php_self;

        if ($currentController === 'category') {
            $this->context->controller->addCSS($this->_path . 'views/css/styleLeftColum.css');
        }

        if ($currentController === 'product') {
            $this->context->controller->addCSS($this->_path . 'views/css/styleProductAdditionalInfo.css');
        }

        if ($currentController === 'cart') {
            $this->context->controller->addCSS($this->_path . 'views/css/styleReassurence.css');
        } else {
            /* cette feuille de style c'est pour cacher le carousel du reassurance des pages produits */
            $this->context->controller->addCSS($this->_path . 'views/css/styleNoneCarousel.css');
        }

        if ($currentController === 'index') {
            $this->context->controller->addCSS($this->_path . 'views/css/styleContentWrapperTop.css');
        }
    }

    /* hook for the video home */
    public function hookDisplayContentWrapperTop($params)
    {
        /* $controllers = 'index';
         $currentController = Tools::getValue('controller');*/
        $currentController = Context::getContext()->controller->php_self;

        if ($currentController !== 'index') {
            return '';
        }

        $this->context->smarty->assign([
            'url' => Configuration::get('URL_VIDEO'),
            'pathVideo' => Configuration::get('PATH_VIDEO'),
            'height' => (int) Configuration::get('HEIGHT_VIDEO'),
            'videoType' => (int) Configuration::get('TYPE_VIDEO'),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/hookDisplayContentWrapperTop.tpl');
    }

    /* hook for the video cart */
    public function hookDisplayLeftColumn($params)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from('youtubevideopluscarousel');

        $data = Db::getInstance()->executes($sql);

        $this->context->smarty->assign([
            'data' => $data,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/hookDisplayLeftColumn.tpl');
    }

    /* hook for the video cart */
    public function hookDisplayReassurance($params)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from('youtubevideopluscarousel');

        $data = Db::getInstance()->executes($sql);

        $this->context->smarty->assign([
            'data' => $data,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/hookDisplayReassurance.tpl');
    }

    /* hook for product video */
    public function hookDisplayProductAdditionalInfo($params)
    {
        $product = new Product(Tools::getValue('id_product'));

        $this->context->smarty->assign([
            'urlVideo' => $product->urlVideo,
        ]
        );

        return $this->display(__FILE__, 'views/templates/hook/hookDisplayProductAdditionalInfo.tpl');
    }

    /* hook for product video onglet */
    public function hookDisplayAdminProductsExtra($params)
    {
        $product = new Product(Tools::getValue('id_product'));

        $this->context->smarty->assign([
            'urlVideo' => $product->urlVideo,
            'videoType' => $product->videoType,
        ]
        );

        return $this->display(__FILE__, 'views/templates/hook/hookDisplayAdminProductsExtra.tpl');
    }
}
