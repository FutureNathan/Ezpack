
function findBox (number) {
  
  const boxes = {
    // name :             [height,width,length,weight,volum,price]
    
    'J 8':                [8, 5, 3, 2, 120, 2.49],
    'Cellphone':          [10, 6, 3, 2, 180, 6.99],
    '6 Cube':             [6, 6, 6, 2, 216, 3.25],
    'J 11':               [10, 7, 4, 2, 280, 2.99],
    '8 Cube':             [8, 8, 8, 2, 512, 3.99],
    'VJ 14':              [12, 9, 6, 3, 648, 4.99],
    'J 15':               [13, 11, 5, 3, 715, 4.25],
    'Shirt A':            [16, 10, 5, 2, 800, 4.99],
    'Comcast/Modem':      [12, 12, 6, 4, 864, 6.69],
    '14 x 8 x 8':         [14, 8, 8, 1, 896, 6.99],
    'Umbrella':           [4, 4, 58, 3, 928, 14.99],
    '10 Cube':            [10, 10, 10, 3, 1000, 4.75],
    'Shirt B':            [18, 14, 4, 2, 1008, 5.99],
    'J 16':               [15, 11, 7, 4, 1155, 4.99],
    'Sm. Wreath':         [18, 18, 4, 1, 1296, 7.99],
    'J 17':               [18, 13, 6, 3, 1404, 5.99],
    '17x11x8':            [17, 11, 8, 4, 1496, 6.99],
    'Laptop':             [21, 16, 5, 3, 1680, 31.99],
    '12 Cube':            [12, 12, 12, 3, 1728, 5.99],
    '6x6 Lamp':           [6, 6, 48, 5, 1728, 14.99],
    'Shirt C':            [24, 15, 5, 3, 1800, 6.99],
    'Dyson':              [15, 12, 10, 6, 1800, 6.99],
    'J 20':               [18, 13, 9, 3, 2106, 6.49],
    'Ski (half so 2x)':   [42, 9, 6, 4, 2268, 11.99],
    'Md. Wreath':         [20, 20, 6, 2, 2400, 8.99],
    'PR 8':               [18, 12, 12, 2, 2592, 5.99],
    '24x18x6':            [24, 18, 6, 4, 2592, 11.99],
    'J 22':               [20, 15, 9, 4, 2700, 7.25],
    '14 Cube':            [14, 14, 14, 5, 2744, 6.49],
    'Lg. Wreath':         [24, 24, 6, 3, 3456, 11.99],
    '16 Cube':            [16, 16, 16, 7, 4096, 7.99],
    'J 64':               [22, 15, 13, 4, 4290, 6.99],
    'J 24':               [24, 18, 10, 3, 4320, 9.25],
    '30x24x6':            [30, 24, 6, 5, 4320, 11.90],
    'J 70':               [20, 15, 15, 5, 4500, 7.99],
    'UPS 4':              [22, 18, 12, 6, 4752, 8.99],
    'ST 10':              [20, 20, 12, 5, 4800, 10.99],
    '10x10 Lamp':         [10, 10, 48, 5, 4800, 14.99],
    'VCR/CD':             [20, 20, 12, 6, 4800, 10.99],
    '30" Mirror':         [30, 30, 6, 6, 5400, 15.99],
    '18 Cube':            [18, 18, 18, 8, 5832, 8.50],
    'J 57':               [26, 18, 13, 3, 6084, 9.75],
    'MG 1':               [32, 22, 10, 3, 7040, 14.99],
    'Suitcase':           [24, 10, 31, 6, 7440, 13.99],
    '36" Mirror':         [36, 36, 6, 6, 7776, 16.99],
    '20 Cube':            [20, 20, 20, 9, 8000, 9.99],
    'Guitar':             [20, 8, 50, 6, 8000, 24.99],
    '13x13 Lamp':         [13, 13, 48, 7, 8112, 14.99],
    'Snowboard':          [16, 8, 65, 8, 8320, 29.99],
    '42" Mirror':         [42, 6, 36, 8, 9072, 24.99],
    'UPS 2':              [24, 24, 16, 7, 9216, 17.99],
    '24x24x16':           [24, 24, 16, 4, 9216, 17.99],
    'Golf':               [19, 11, 46, 5, 9614, 24.99],
    'TV':                 [27, 20, 18, 7, 9720, 24.99],
    'UPS 1':              [20, 20, 25, 6, 10000, 11.99],
    '24x24x18':           [24, 24, 18, 3, 10368, 17.99],
    '48" Mirror':         [36, 48, 6, 9, 10368, 24.99],
    'New Golf':           [14, 14, 53, 6, 10388, 24.99],
    '22 Cube':            [22, 22, 22, 1, 10648, 15.99],
    'UPS 3':              [33, 18, 18, 3, 10692, 17.95],
    '15x15 Lamp':         [15, 15, 48, 10, 10800, 24.99],
    'Bike':               [55, 8, 28, 15, 12320, 39.99],
    '24 Cube':            [24, 24, 24, 1, 13824, 17.99],
    '26 Cube':            [26, 26, 26, 1, 17576, 26.99],
    'BB 130':             [34, 22, 24, 15, 17952, 19.99],
    'Wardrobe':           [25, 22, 46, 8, 25300, 34.99],
    'Chair':              [30, 29, 34, 8, 29580, 49.99]
  };
  
  
  var inputArray = [];
  
  boxSizeInputs = document.querySelectorAll('.boxSize > input');
  boxSizeInputs .forEach (function (boxSizeInput) {
    
    if( boxSizeInput.value > 0 ) {
      
      inputArray.push(parseInt(boxSizeInput.value) + number);
       
    }
  }, false );
  
  inputArray = inputArray.sort();
  console.log(inputArray);
  
  var resultNumber = 0;
  
  for (const key in boxes) {
    if (boxes.hasOwnProperty(key)) {
      
      var length = boxes[key][0];
      var width  = boxes[key][1];
      var height = boxes[key][2];
      var name   = key;
      var price  = boxes[key][5];
      
      
      var boxDimensions = new Array();
      
      boxDimensions.push(length);
      boxDimensions.push(width);
      boxDimensions.push(height);
      
      boxDimensions = boxDimensions.sort();
      
    }
    
    
    if(resultNumber < 5) {
      if (inputArray[0] <= boxDimensions[0] && inputArray[1] <= boxDimensions[1] && inputArray[2] <= boxDimensions[2] )  {
        
        var structure = `
        <section class="resultboxContainer">
        <h1 class="boxName">${name}</h1>
        <span class="boxType">UPS Box</span>
        <span class="boxPrice">$${price}</span>
        <span class="boxDimension">${height}''</span>
        <span class="boxDimension">${width}''</span>
        <span class="boxDimension">${length}''</span>
        </section>
        `;
        
        var resultsContainer = document.querySelector('.results > div');
        
        var domParserContent = new DOMParser().parseFromString (structure, 'text/html');
        
        var resultboxContainer = domParserContent.querySelector('.resultboxContainer');
        
        resultsContainer.appendChild (resultboxContainer);
        
        resultNumber = parseInt(resultNumber) + 1;
      }
      
    }
  
  };
  
  return inputArray;
}
