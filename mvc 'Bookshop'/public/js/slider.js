'use strict';
    var multiItemSlider = (function () {
      return function (selector, config) {
          var  _mainElement = document.querySelector(selector); // основный элемент блока
          var _sliderWrapper = _mainElement.querySelector('.slider-wrapper'); // обертка для .slider-item
          var _sliderItems = _mainElement.querySelectorAll('.slider-item'); // элементы (.slider-item)
          var _sliderControls = _mainElement.querySelectorAll('.slider-item-button'); // элементы управления
          var _sliderControlLeft = _mainElement.querySelector('.left-button'); // кнопка "LEFT"
          var _sliderControlRight = _mainElement.querySelector('.right-button'); // кнопка "RIGHT"
          var _wrapperWidth = parseFloat(getComputedStyle(_sliderWrapper).width); // ширина обёртки
          var _itemWidth = parseFloat(getComputedStyle(_sliderItems[0]).width); // ширина одного элемента    
          var _positionLeftItem = 0; // позиция левого активного элемента
          var _transform = 0; // значение транфсофрмации .slider_wrapper
          var _step = _itemWidth / _wrapperWidth * 100; // величина шага (для трансформации)
          var _items = []; // массив элементов
          var timer = null;
          var interval = 3500;

        // наполнение массива _items
        _sliderItems.forEach(function (item, index) {
          _items.push({ item: item, position: index, transform: 0 });
        });

        var position = {
          getItemMin: function () {
            var indexItem = 0;
            _items.forEach(function (item, index) {
              if (item.position < _items[indexItem].position) {
                indexItem = index;
              }
            });
            return indexItem;
          },
          getItemMax: function () {
            var indexItem = 0;
            _items.forEach(function (item, index) {
              if (item.position > _items[indexItem].position) {
                indexItem = index;
              }
            });
            return indexItem;
          },
          getMin: function () {
            return _items[position.getItemMin()].position;
          },
          getMax: function () {
            return _items[position.getItemMax()].position;
          }
        }

        var _transformItem = function (direction) {
          var nextItem;
          if (direction === 'right') {
            _positionLeftItem++;
            if ((_positionLeftItem + _wrapperWidth / _itemWidth - 1) > position.getMax()) {
              nextItem = position.getItemMin();
              _items[nextItem].position = position.getMax() + 1;
              _items[nextItem].transform += _items.length * 100;
              _items[nextItem].item.style.transform = 'translateX(' + _items[nextItem].transform + '%)';
            }
            _transform -= _step;
          }
          if (direction === 'left') {
            _positionLeftItem--;
            if (_positionLeftItem < position.getMin()) {
              nextItem = position.getItemMax();
              _items[nextItem].position = position.getMin() - 1;
              _items[nextItem].transform -= _items.length * 100;
              _items[nextItem].item.style.transform = 'translateX(' + _items[nextItem].transform + '%)';
            }
            _transform += _step;
          }
          _sliderWrapper.style.transform = 'translateX(' + _transform + '%)';
        }

        // обработчик события click для кнопок "назад" и "вперед"
        var _controlClick = function (e) {
          if (e.target.classList.contains('slider-item-button')) {
            e.preventDefault();
            var direction = e.target.classList.contains('right-button') ? 'right' : 'left';
            _transformItem(direction);
            clearInterval(timer)
            timer = setInterval(() => (_transformItem('right')), interval);
          }
        };

        var _setUpListeners = function () {
          // добавление к кнопкам "назад" и "вперед" обрботчика _controlClick для событя click
          _sliderControls.forEach(function (item) {
            item.addEventListener('click', _controlClick);
          });
        }

        // инициализация
        _setUpListeners();
        timer = setInterval(() => (_transformItem('right')), interval);
        return {
          right: function () { // метод right
            _transformItem('right');
          },
          left: function () { // метод left
            _transformItem('left');
          }
        }

      }
    }());

    var slider = multiItemSlider('.slider')