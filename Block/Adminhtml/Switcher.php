<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_AdminLocaleSwitcher
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

namespace Qoliber\AdminLocaleSwitcher\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Locale\OptionInterface;

class Switcher extends Template
{
    /** @var string  */
    protected $_template = 'Qoliber_AdminLocaleSwitcher::switcher.phtml';

    protected Session $authSession;

    protected OptionInterface $localeOptions;

    public function __construct(
        OptionInterface $localeOptions,
        Session $authSession,
        Context $context
    ) {
        $this->localeOptions = $localeOptions;
        $this->authSession = $authSession;
        parent::__construct($context, []);
    }

    /**
     * Get Admin User Interface Locale
     */
    public function getUserLocaleIcon(): string
    {
        $user = $this->authSession->getUser();
        $userLocal = !empty($user) ?$user->getInterfaceLocale() : 'en_US';
        return $this->getViewFileUrl(sprintf('Qoliber_AdminLocaleSwitcher/locale-flags/%s.svg', $userLocal));
    }

    public function getCountryListJSON(): string|false
    {
        $select2Countries = [];
        $locales = $this->localeOptions->getTranslatedOptionLocales();
        foreach ($locales as $locale) {
            $select2Countries[] = [
                'id' => $locale['value'],
                'text' => $locale['label'],
                'flag' => $this->getViewFileUrl(sprintf('Qoliber_AdminLocaleSwitcher/locale-flags/%s.svg', $locale['value'])),
            ];
        }

        return json_encode($select2Countries);
    }
}
