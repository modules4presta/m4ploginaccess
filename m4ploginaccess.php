<?php

/**
 * LICENCE
 *
 * ALL RIGHTS RESERVED.
 * YOU ARE NOT ALLOWED TO COPY/EDIT/SHARE/WHATEVER.
 *
 * IN CASE OF ANY PROBLEM CONTACT AUTHOR.
 *
 *  @author    Jan Kołodziej (contact@modules4presta.io)
 *  @copyright Modules4Presta.io
 *  @license   ALL RIGHTS RESERVED
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class M4pLoginAccess extends Module
{
    public function __construct()
    {
        $this->name = 'm4ploginaccess';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Modules4Presta.io';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Login Access Control');
        $this->description = $this->l('Enforce login and block possible to buy products for guests.');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayProductPriceBlock');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit'.$this->name)) {
            $force_login = Tools::getValue('M4PLOGINACCESS_FORCE_LOGIN');
            $hide_prices = Tools::getValue('M4PLOGINACCESS_HIDE_PRICES');

            Configuration::updateValue('M4PLOGINACCESS_FORCE_LOGIN', (int) $force_login);
            Configuration::updateValue('M4PLOGINACCESS_HIDE_PRICES', (int) $hide_prices);

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enforce login site-wide'),
                        'name' => 'M4PLOGINACCESS_FORCE_LOGIN',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Block possible to buy products and see prices for guests'),
                        'name' => 'M4PLOGINACCESS_HIDE_PRICES',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit'.$this->name;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value['M4PLOGINACCESS_FORCE_LOGIN'] = Configuration::get('M4PLOGINACCESS_FORCE_LOGIN');
        $helper->fields_value['M4PLOGINACCESS_HIDE_PRICES'] = Configuration::get('M4PLOGINACCESS_HIDE_PRICES');

        return $helper->generateForm(array($fields_form));
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (
            (bool)Configuration::get('M4PLOGINACCESS_HIDE_PRICES')
            && empty($this->context->customer->isLogged())
            && $params['type'] == 'price'
        ) {
            return '<p class="m4ploginaccess-note">'.$this->l('Please log in to buy products and see prices.').'</p>';
        }
    }

    private function checkIfCustomerIsOnAccessPages()
    {
        $basePath = Tools::getHttpHost(true);

        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);

        $relative = $path;
        if (strpos($path, $basePath) === 0) {
            $relative = substr($path, strlen($basePath));
        }

        $relative = ltrim($relative, '/');
    }

    public function hookDisplayHeader()
    {
        if (
            (bool)Configuration::get('M4PLOGINACCESS_HIDE_PRICES')
            && empty($this->context->customer->isLogged())
        ) {
            $this->context->controller->addCSS($this->_path.'views/css/loginaccess.css');
        }

        if (
            (bool)Configuration::get('M4PLOGINACCESS_FORCE_LOGIN')
            && empty($this->context->customer->isLogged())
            && !in_array($this->context->controller->php_self, ['authentication', 'registration', 'password', 'cms', 'contact'])
        ) {
            Tools::redirect('index.php?controller=authentication&back=' . urlencode($_SERVER['REQUEST_URI']));
        }
    }
}
