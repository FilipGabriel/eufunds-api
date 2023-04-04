import Downloads from './Downloads';
import ProductForm from './ProductForm';

new ProductForm();

if($('.product-downloads-wrapper').length !== 0) {
    new Downloads();
}
