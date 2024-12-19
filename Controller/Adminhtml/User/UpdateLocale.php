<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_AdminLocaleSwitcher
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

namespace Qoliber\AdminLocaleSwitcher\Controller\Adminhtml\User;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\User\Model\ResourceModel\User;

class UpdateLocale extends Action implements HttpPostActionInterface
{
    protected Session $session;

    protected User $userResource;

    public function __construct(
        Session $session,
        Context $context,
        User $userResource
    ) {
        $this->session = $session;
        $this->userResource = $userResource;
        parent::__construct($context);
    }

    /**
     * @throws AlreadyExistsException
     */
    public function execute(): Redirect
    {
        $locale = $this->_request->getParam('admin-locale');
        $user = $this->session->getUser();
        if (!empty($user)) {
            $user->setInterfaceLocale($locale);
            $this->userResource->save($user);
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        $url = $this->_redirect->getRefererUrl();
        $resultRedirect->setPath(
            $url
        );

        $this->messageManager->addSuccessMessage(
            __('Your locale settings have been updated')
        );

        return $resultRedirect;
    }
}
