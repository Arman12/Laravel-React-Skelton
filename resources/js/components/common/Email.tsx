import React, { ChangeEvent, FocusEvent, useState } from 'react';
import { validateEmail } from './../../utils/emailUtils';
interface InputProps {
  name: string;
  id?: string;
  className?: string;
  value?: string;
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
 * Email.
 *
 * All rights Reseverd | Arhamsoft Pvt @2023
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
function Email(props: InputProps) {
  const { name, id = 'id-' + props.name, className = 'input-field', value, placeholder = 'Enter Email', required, onChange, label, divClass = 'form-group', hasError, errorMessage = 'Please enter email address', setHasError } = props;
  let [blurred, setBlurred] = useState<number>(2);
  let [isValid, setIsValid] = useState<boolean>(false);
  
  
  /**
   * Handles changes to the input value.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The change event.
   * @returns {void}
   */
  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setBlurred(0);
    if (onChange) {
      onChange(event);
      const value = event.target.value;
      if (value) {
        setHasError(!validateEmail(value));
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
    setBlurred(1);
    const value = event.target.value;
    if (value) {
      setIsValid(validateEmail(value));
    } else if (required) {
      setIsValid(true);
    } else {
      setIsValid(false);
    }
  };

  return (
    <div className={divClass}>
      {label && <label htmlFor={id}>{label} {required && <span>*</span>}</label>}
      <input
        type='email'
        name={name}
        id={id}
        className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className}
        value={value}
        placeholder={placeholder}
        required={required}
        onChange={handleChange}
        onBlur={handleBlur}
      />
      {(hasError && blurred > 0) && <p className='error'>{errorMessage}</p>}
    </div>
  );
};

export default Email;