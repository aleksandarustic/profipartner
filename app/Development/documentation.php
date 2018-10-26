<?php 


/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Profi Partner",
 *     ),
 *     @OA\Server(
 *         description="Profi Partner",
 *         url="http://localhost:8000/api/mobile"
 *     )
 * )
 * 
 */

 


/**
 * CARDS
 */


     /**
     * @OA\Get(
     *   path="/cards",
     *   tags={"cards"},
     *   summary="Get all customer cards",
     *   description="Returns all customer cards",
     *   operationId="get_cards",
     *   parameters={
     *   @OA\Parameter(
     *     name="Accept:",
     *     in="header",
     *     description="ID of pet that needs to be fetched",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         format="int64",
     *     )
     *   ),
     * },
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(
     *       additionalProperties={
     *         "type":"integer",
     *         "format":"int32"
     *       }
     *     )
     *   ),
     *   security={{
     *     "api_key":{}
     *   }}
     * )
     */


  /**
     * @OA\Get(path="/cards/{id}",
     *   tags={"cards"},
     *   summary="Find card by ID",
     *   description="Gets customer card",
     *   operationId="get_card",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID of pet that needs to be fetched",
     *     required=true,
     *     @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         minimum=1.0,
     *         maximum=10.0
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(ref="#/components/schemas/Order")
     *   ),
     *   @OA\Response(response=400, description="Invalid ID supplied"),
     *   @OA\Response(response=404, description="Order not found")
     * )
     */

      /**
     * @OA\Post(path="/user",
     *   tags={"user"},
     *   summary="Create user",
     *   description="This can only be done by the logged in user.",
     *   operationId="createUser",
     *   @OA\RequestBody(
     *       required=true,
     *       description="Created user object",
     *     
     *   ),
     *   @OA\Response(response="default", description="successful operation")
     * )
     */