<?php

/**
 * Class for storing uri consts
 */
class uriConst{
    const public = "public";
    const orders ="orders";
    const customer="customer";
    const shipment="shipment";
    const storekeeper="storekeeper";
    const productionPlans="production-plans";

}

/**
 * Class for storing uri consts
 */
class httpErrorConst{
    // 2xx Success
    const OK = 200;

    // 4xx Client Error
    const badRequest = 400;
    const unauthorized = 401;
    const notFound = 404;

    // 5xx Server Error
    const serverError = 500;
}
