<?php

/**
 * m4ploginaccess
 *
 * @author    Modules4Presta <contact@modules4presta.io>
 * @copyright 2026 Nice Code sp. z o.o. (Modules4Presta)
 * @license   https://opensource.org/licenses/MIT MIT License
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
        $this->ps_versions_compliancy = ['min' => '1.7.6.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('Login Access Control', [], 'Modules.M4ploginaccess.Admin');
        $this->description = $this->trans('Enforce login and block possible to buy products for guests.', [], 'Modules.M4ploginaccess.Admin');
    }

    public function install()
    {
        Configuration::updateValue('M4PLOGINACCESS_FORCE_LOGIN', 0);
        Configuration::updateValue('M4PLOGINACCESS_HIDE_PRICES', 0);

        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayProductPriceBlock');
    }

    public function uninstall()
    {
        Configuration::deleteByName('M4PLOGINACCESS_FORCE_LOGIN');
        Configuration::deleteByName('M4PLOGINACCESS_HIDE_PRICES');

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

            $output .= $this->displayConfirmation($this->trans('Settings updated', [], 'Modules.M4ploginaccess.Admin'));
        }

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', [], 'Modules.M4ploginaccess.Admin'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Enforce login site-wide', [], 'Modules.M4ploginaccess.Admin'),
                        'name' => 'M4PLOGINACCESS_FORCE_LOGIN',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Enabled', [], 'Modules.M4ploginaccess.Admin')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Disabled', [], 'Modules.M4ploginaccess.Admin')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Block possible to buy products and see prices for guests', [], 'Modules.M4ploginaccess.Admin'),
                        'name' => 'M4PLOGINACCESS_HIDE_PRICES',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Enabled', [], 'Modules.M4ploginaccess.Admin')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Disabled', [], 'Modules.M4ploginaccess.Admin')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', [], 'Modules.M4ploginaccess.Admin'),
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
            return '<p class="m4ploginaccess-note">'.$this->trans('Please log in to buy products and see prices.', [], 'Modules.M4ploginaccess.Shop').'</p>';
        }
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
