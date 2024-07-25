import React, { ChangeEvent, FocusEvent, useState } from 'react';
interface InputProps {
  type: string;
  name: string;
  id?: string;
  className?: string;
  value?: string | number;
  label?: string;
  placeholder?: string;
  required?: boolean;
  onChange: (event: ChangeEvent<HTMLInputElement>) => void;
  divClass?: string;
  hasError?: boolean;
  errorMessage?: string;
  setHasError: (isValid: boolean) => void;

}

/**
 * Phone Input.
 *
 * 
 * @param {Object} props - Component props.
 * @param {string} props.name - Name attribute for the input element.
 * @param {boolean} props.required - Indicates if the input is required.
 * @param {string} props.value - value for the input element.
 * @param {string} [props.className] - Optional to dynamiclly add the classs.
 * @param {string} [props.label] - dynamiclly add label to the element.
 * @param {string} [props.id] - dynamiclly add id to the element.
 * @param {ChangeEvent<HTMLInputElement>} [props.onChange] - callback.
 * @param {string} [props.divClass] - dynamiclly add id to the parnt element.
 * @param {boolean} [props.hasError] - dynamiclly pass the true/false value.
 * @param {string} [props.errorMessage] - dynamiclly pass the error message.
 * @param {ChangeEvent<HTMLInputElement>} [props.setHasError] - callback
 * 
 * @returns {JSX.Element} - JSX representation of the component.
 */

function Input(props: InputProps) {
  const { type, name, id = 'id-' + props.name, className = 'input-field', value, placeholder = 'Enter ' + (props.label) ? props.label : props.name, required, onChange, label, divClass = 'form-group', hasError, errorMessage = 'Please enter ' + (props.label) ? props.label : props.name, setHasError } = props;
  let [isValid, setIsValid] = useState<boolean>(false);
  
  
  /**
   * Handles changes to the input value.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The change event.
   * @returns {void}
   */
  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (onChange) {
      onChange(event);
      const value = event.target.value;
      if (value) {
        setHasError(false);
      } else if (required) {
        setHasError(true);
      }
    }
  };


  /**
   * Handles changes to the input value.
   *
   * @param {FocusEvent<HTMLInputElement>} event - The blur event.
   * @returns {void}
   */
  const handleBlur = (event: FocusEvent<HTMLInputElement>) => {
    const value = event.target.value;
    if (value) {
      setIsValid(true);
    } else {
      setIsValid(false);
    }
  };
  return (
    <div className={divClass}>
      {label && <label htmlFor={id}>{label} {required && <span>*</span>}</label>}
      <input
        type={type}
        name={name}
        id={id}
        className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className}
        value={value}
        placeholder={placeholder}
        required={required}
        onChange={handleChange}
        onBlur={handleBlur}
      />
      {hasError && <p className='error'>{errorMessage}</p>}
    </div>
  );
};

export default Input;