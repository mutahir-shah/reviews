<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Store;

use App\Domain\Store\Store;
use App\Domain\Store\StoreNotFoundException;
use App\Domain\Store\StoreRepository;
use App\DbClasses\PDOdb;
use Memcached;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class InMemoryStoreRepository implements StoreRepository
{
    /**
     * @var Store[]
     */
    private $stores;

    /**
     * InMemoryStoreRepository constructor.
     *
     * @param array|null $stores
     */
    /**
     * @var PDO The database connection
     */
    private $db;
    private $cache; 

    public function __construct( array $stores = null, PDOdb $connection, \Memcached $cache)
    {
        $this->stores = $stores ?? [];
        $this->db     = $connection;
        $this->cache  = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $cols = "culture_name";
        $this->db->select('nl_culture', $cols);
        $result = $this->db->fetch();

        return array_values($result);
    }
    /**
     * {@inheritdoc}
     */
    public  function getDomainData(string $external_key): array
    {
        $keyName      = md5('get_DomainData_New' . $external_key);
        $domain_data  = $this->cache->get($keyName);

        if (!$domain_data) {
            $fields = "name,status,disabled_categories,hidden_stores,master_hidden_stores,
     is_cashback,percentage_model,cultureid";
            $table = 'nl_domains';
            $this->db->where(
                ['external_key', $external_key, '='],
                ['status', 'active', '=']
            );
            $this->db->select($table, $fields);
            $domain_data = $this->db->fetch(PDOdb::_FETCH_ONE);

            $hidden_stores         = $domain_data['hidden_stores'];
            $master_hidden_stores  = $domain_data['master_hidden_stores'];
            $excluded_stores       = '';
            if ($hidden_stores != null) {
                $excluded_stores .= $hidden_stores;
                if ($master_hidden_stores != null) {
                    $excluded_stores .= ',';
                }
            }
            if ($master_hidden_stores != null) {
                $excluded_stores .= $master_hidden_stores;
            }
            $domain_data['excluded_stores'] = trim($excluded_stores, ",");
            $this->cache->set($keyName, $domain_data, 21600);
        }
        return $domain_data;
    }

    public function allStores(): array
    { 
        $domain_data = $this->getDomainData('3XUYxdiiklf4910PHuU');
        echo '<pre>';
        print_r($domain_data);
        exit;

        $fields = "distinct(s.storeid) AS storeId, s.name AS storeName, s.currencyid, s.cashback AS cashBack,override_partner_cashback_model AS overrideCashback,
        s.imageurl  AS imageUrl,np.commision,s.short_desc AS storeDescription,s.currencyid, s.cashback_type AS cashbackType,s.url_key,s.website_url as storeURL";

        $this->db->where(
            ['s.is_hidden', '0', '='],
            ['s.cultureid', '1', '='],
            ['s.status', '1', '=']
        );

        $tables = [
            ['nl_store', 'as' => 's'],
            [
                'nl_store_performance', 'as' => 'np', 'join' => 'left',
                'on' => 'np.storeid=s.storeid'
            ],
            [
                'nl_currency', 'as' => 'nc', 'join' => 'inner',
                'on' => 's.currencyid=nc.currencyid'
            ]
        ];
        $limit = 5;
        $offSet = 0;
        $keywords = [
            'limit' => $limit,
            'order_by' => 'np.commision DESC',
            'offset' => $offSet
        ];
        $this->db->select($tables, $fields, $keywords);
        $result = $this->db->fetch();
        $this->db->where(
            ['s.is_hidden', '0', '='],
            ['s.cultureid', '1', '='],
            ['s.status', '1', '=']
        );

        $this->db->select($tables, "count(*)");
        $count = $this->db->fetch(PDOdb::_FETCH_ONE_FIELD);

        return array_values($result);
    }
}
