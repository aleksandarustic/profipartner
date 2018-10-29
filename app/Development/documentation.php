<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Profi Partner",
 *     ),
 *     @OA\Server(
 *         description="Profi Partner",
 *         url=L5_SWAGGER_CONST_HOST
 *     )
 * )
 *
 */




/**
 * CUSTOMERS
 */

 /**
 *
 * @OA\Get(
 *   path="/customers",
 *   tags={"Customers"},
 *   summary="Get current customer ",
 *   description="Returns info about authetificate customer",
 *   operationId="get_user",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *         )
 *     ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

 /**
 * @OA\Post(
 *     path="/register/customer",
 *     tags={"Customers"},
 *     operationId="register_customer",
 *     summary="Get api key for customer and register customer if he does not exist in database",
 *     description="Return customer info and add customer if nessery",
 *     @OA\RequestBody(
 *         description="Customer object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/Customer")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Put(
 *     path="/customers",
 *     tags={"Customers"},
 *     operationId="update_customer",
 *     summary="Update customer info",
 *     description="",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *     @OA\RequestBody(
 *         description="Customer object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Customer")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */


/**
 * CARDS
 */

/**
 *
 * @OA\Get(
 *   path="/cards",
 *   tags={"Cards"},
 *   summary="Get all customer cards",
 *   description="Returns all customer cards",
 *   operationId="get_cards",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *         )
 *     ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Get(path="/cards/{id}",
 *   tags={"Cards"},
 *   summary="Find card by ID",
 *   description="Gets customer card",
 *   operationId="get_card",
 *   @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of loyalty card",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Post(
 *     path="/cards",
 *     tags={"Cards"},
 *     operationId="add_card",
 *     summary="Add new loyalty user card",
 *     description="",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *     @OA\RequestBody(
 *         description="Card object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/Card")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Post(
 *     path="/cards/{id}",
 *     tags={"Cards"},
 *     operationId="update_card",
 *     summary="Update loyalty card",
 *     description="",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *   @OA\Parameter(
 *         name="_method",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *            default="PUT",
 *        )
 *     ),
 *    @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of loyalty card",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *     @OA\RequestBody(
 *         description="Card object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/Card")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

 /**
 * @OA\Delete(path="/cards/{id}",
 *   tags={"Cards"},
 *   summary="Delete loyalty card",
 *   description="Delete loyalty card",
 *   operationId="delete_card",
 *   @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of loyalty card",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

 /**
  * REWARDS
  */


/**
 *
 * @OA\Get(
 *   path="/rewards",
 *   tags={"Rewards"},
 *   summary="Get all rewards",
 *   description="Returns all rewards",
 *   operationId="get_rewards",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *         )
 *     ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Get(path="/rewards/{id}",
 *   tags={"Rewards"},
 *   summary="Find reward by ID",
 *   description="Gets reward",
 *   operationId="get_reward",
 *   @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of reward",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

 /**
  * ORDERS
  */


/**
 *
 * @OA\Get(
 *   path="/orders",
 *   tags={"Orders"},
 *   summary="Get all customer orders",
 *   description="Returns all customer orders",
 *   operationId="get_cards",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *         )
 *     ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Get(path="/orders/{id}",
 *   tags={"Orders"},
 *   summary="Find order by ID",
 *   description="Gets customer order",
 *   operationId="get_order",
 *   @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of order",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Post(
 *     path="/orders",
 *     tags={"Orders"},
 *     operationId="add_order",
 *     summary="Add new order",
 *     description="",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *     @OA\RequestBody(
 *         description="Order object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/Order")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Put(
 *     path="/orders/{id}",
 *     tags={"Orders"},
 *     operationId="update_order",
 *     summary="Update order",
 *     description="",
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *        )
 *     ),
 *    @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="ID of Order",
 *     required=true,
 *     @OA\Schema(
 *         type="integer",
 *         format="int64",
 *         minimum=1.0,
 *     )
 *   ),
 *     @OA\RequestBody(
 *         description="Order object",
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Order")
 *         ),
 *     ),
 *       @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *       mediaType="application/json"
 *     ),
 *   ),
 * )
 */