/**
 * Creates a new DOM element inside the specified container, according to the structure and options that are provided.
 * 
 * ----------
 * 
 * @param object  structure     The new element's structure options. Only "tag" is required, the other ones are optional.
 * @param element container     The new element's container (parent element).
 * @param element positionRef   The new element will be placed in the DOM immediately before this reference element.
 * 
 * If no reference element is provided (null), the new element will be appended to the container as its last child.
 * 
 * ----------
 * 
 * The new element's structure should be provided like below:
 * 
 * structure = {
 *  tag: '',
 *  
 *  attributes: {
 *    name: 'value',
 *    ...
 *  },
 *  
 *  // can be "structure" objects or text
 *  // they are appended in order, one after the other
 *  content: [
 *    structure,
 *    'text',
 *    ...
 *  ],
 *  
 *  listeners: {
 *    event: [function, ...],
 *    ...
 *  }
 * }
 * 
 */

function insertElement (structure, container, positionRef) {
  
  if ((typeof structure !== 'object') || (structure === null)) {
    return null;
  }
  
  if ( ! (container instanceof Element)) {
    return null;
  }
  
  if ( ! (positionRef instanceof Element)) {
    positionRef = null;
  }
  
  // ----------
  
  var newElement = document.createElement (structure.tag);
  
  // ----------
  
  if ((typeof structure.attributes === 'object') && (structure.attributes !== null)) {
    
    Object.keys (structure.attributes).forEach (function(attributeName) {
      
      newElement.setAttribute (attributeName, structure.attributes[attributeName]);
    });
  }
  
  // ----------
  
  if (Array.isArray (structure.content)) {
    
    structure.content.forEach (function(elementContent) {
      
      if (typeof elementContent === 'object') {
        
        insertElement (elementContent, newElement, null);
        
      } else if (typeof elementContent === 'string') {
        
        newElement.appendChild (document.createTextNode (elementContent));
      }
    });
  }
  
  // ----------
  
  if ((typeof structure.listeners === 'object') && (structure.listeners !== null)) {
    
    Object.keys (structure.listeners).forEach (function(eventName) {
      
      structure.listeners[eventName].forEach (function(funcName) {
        
        newElement.addEventListener (eventName, funcName, false);
      });
    });
  }
  
  // ----------
  
  container.insertBefore (newElement, positionRef);
  
}
