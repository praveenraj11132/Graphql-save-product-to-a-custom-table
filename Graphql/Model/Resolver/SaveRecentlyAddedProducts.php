<?php

namespace Wheelpros\Graphql\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Wheelpros\Graphql\Model\DataModel;

class SaveRecentlyAddedProducts implements ResolverInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var DataModel
     */
    protected DataModel $dataModelFactory;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param DataModel $dataModelFactory
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        DataModel $dataModelFactory
    )
    {
        $this->productRepository = $productRepository;
        $this->dataModelFactory = $dataModelFactory;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return bool
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): bool
    {
        // Check if the user has the necessary permissions to execute the mutation
        if (!$this->isAuthorized($context)) {
            throw new GraphQlAuthorizationException(__('You are not authorized to perform this action.'));
        }

        $productId = $args['productId'];

        // Retrieve the product by ID
        $product = $this->getProductById($productId);

        if (!$product) {
            throw new GraphQlInputException(__('Invalid product ID.'));
        }

        // Save the product data in the custom database table
        $this->saveProductToDatabase($product);

        return true;
    }

    /**
     * @param $productId
     * @return ProductInterface|null
     */
    protected function getProductById($productId): ?ProductInterface
    {
        try {
            return $this->productRepository->getById($productId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param $context
     * @return bool
     */
    protected function isAuthorized($context): bool
    {
        // Implement your authorization logic here
        // You can access the current user's information from the $context variable
        // For example, you can check if the user has the necessary role or permissions to perform the action
        // Return true if authorized, or false otherwise
        // Replace this return statement with your authorization logic

        return true; // Placeholder, replace with your authorization logic
    }

    /**
     * @param $product
     * @return void
     */
    protected function saveProductToDatabase($product): void
    {
        // Create a new instance of your data model
        $dataModel = $this->dataModelFactory->create();

        // Set the necessary data in the data model
        $dataModel->setName($product->getName());
        $dataModel->setSku($product->getSku());
        // Save the data model
        $dataModel->save();
    }
}
