<?php

namespace Classes;

use DrewM\MailChimp\MailChimp;

/**
 * Class MailChimpClient
 */
class MailChimpClient
{
    /**
     * @var
     */
    protected $_apiKey;

    /**
     * @var MailChimp
     */
    protected $_mailChimp;

    /**
     * MailChimpClient constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
        $this->_mailChimp = new MailChimp($this->_apiKey);
    }

    /**
     * @return array|false
     */
    public function getLists()
    {
        return $this->_mailChimp->get('lists');
    }

    /**
     * @param $listId
     *
     * @return array|false
     */
    public function getListInfo($listId)
    {
        return $this->_mailChimp->get("lists/$listId");
    }

    /**
     * @param $listId
     *
     * @return array|false
     */
    public function getListMembers($listId)
    {
        return $this->_mailChimp->get("lists/$listId/members");
    }

    /**
     * @param array $data
     *
     * @return array|false
     */


    public function subscribe($idList, \Classes\UserEntity $usuario)
    {
        try{
            $url = sprintf('lists/%s/members', $idList);
            $result = $this->_mailChimp->post($url, [
                'email_address' => $usuario->getEmail(),
                'status'        => 'subscribed',
            ]);
            return $result;
        }
        catch (Exception $exception)
        {
            return $exception->getMessage();
        }
    }

    /**
     * @param array $data
     *
     * @return array|false
     */
    public function updateMember($idList, \Classes\UserEntity $usuario)
    {
        $subscriber_hash = $this->_mailChimp->subscriberHash($usuario->getEmail());
        $url = sprintf('lists/%s/members/%s', $idList, $subscriber_hash);

        $result = $this->_mailChimp->patch($url, [
            'merge_fields' => [
                'FNAME'=> $usuario->getName(),
                'LNAME'=> $usuario->getLastname(),
                'PHONE'=> $usuario->getTelephone(),
                'ADDRESS'=> $usuario->getAddress(), //FIX
                'DATE_BIRTH'=> $usuario->getDateBirth() //FIX
            ]
        ]);

        return $result;
    }

    /**
     * @param array $data
     */
    public function unsubscribe($idList, \Classes\UserEntity $usuario)
    {
        $subscriber_hash = $this->_mailChimp->subscriberHash($usuario->getEmail());
        $url = sprintf('lists/%s/members/%s', $idList, $subscriber_hash);
        $this->_mailChimp->delete($url);
    }

    /**
     * @param $listId
     * @param array $data
     */
    public function subscribeBatch($listId, Array $data = [])
    {
        $batch = $this->_mailChimp->new_batch();
        $url = sprintf('lists/%s/members', $listId);
        foreach ($data as $index => $customer)
        {
            $dataCustomer = [
                'email_address' => $customer->getEmail(),
                'status'        => 'subscribed',
            ];
            $currentOp = sprintf('op%s', ++$index);
            $batch->post($currentOp, $url, $dataCustomer);
        }
        $batch->execute();
    }
}
