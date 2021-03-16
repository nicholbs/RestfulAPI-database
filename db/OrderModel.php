<?php
require_once 'DB.php';
require_once 'AbstractModel.php';

/**
 * Class OrderModel class for accessing used car data in database.
 */
class OrderModel extends AbstractModel {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the collection of resources from the database.
     * @param array $query an optional set of conditions that the retrieved
     *              resources need to meet - e.g., array('make' => 'Ford') would
     *              mean that only resources having make = Ford would be returned.
     * @return array an array of associative arrays of resource attributes. The
     *               array will be empty if there are no resources to be returned.
     * @throws APIException if the $query variable is incorrectly formatted
     */
   function getCollection(array $query = null): array

    {
        //echo("\n Hit kom jeg \n");
        $kommando = $this ->db ->query("SELECT * FROM ski_types");
        $resultatfradb =$kommando ->fetchAll();


        //print_r($resultatfradb); //skriver ut hele arrayen.

        return $resultatfradb;
        
    }

    /**
     * Returns the collection of resources from the database.
     * @param int $id the id of the resource to be retrieved.
     * @return array an associative array of resource attributes - or null if
     *               no resources have the given id.
     */
    function getResource(int $id): array
    {

    }

    /**
     * Creates a new resource in the database.
     * @param array $resource the resource to be created.
     * @return array an associative array of resource attributes representing
     *               the resource - the returned value will include the id
     *               assigned to the resource.
     */
    function createResource(array $resource): array
    {

    }

    /**
     * Modifies a resource in the database.
     * @param array $resource the resource to be modified.
     * @return array an associative array of resource attributes representing
     *               the resource after being modified.
     * @throws APIException if the $resource is missing some of the required
     *                      attributes.
     */
    function updateResource(array $resource): array
    {

    }

    /**
     * Deletes a resource from the database.
     * @param int $id the id of the resource to be deleted.
     * @throws APIException if the record cannot be deleted from the database,
     *                      e.g., due to foreign key constraints.
     */
    function deleteResource(int $id)
    {

    }
}