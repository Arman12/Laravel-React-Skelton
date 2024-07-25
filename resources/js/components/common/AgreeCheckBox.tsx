import React, { ChangeEvent, ReactNode, useState } from 'react';
interface InputProps {
  name: string;
  id?: string;
  className?: string;
  required?: boolean;
  onChange?: (event: ChangeEvent<HTMLInputElement>) => void;
  divClass?: string;
  hasError?: boolean;
  errorMessage?: string;
  value: string;
  setHasError: (isValid: boolean) => void;
  children: ReactNode;
}

/**
 * Phone AgreeCheckBox.
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

function AgreeCheckBox(props: InputProps) {
  const { name, id = 'id-' + props.name, className = 'input-field', required, onChange, divClass = 'form-group agree-terms', hasError, errorMessage = 'Please click in box to agree', setHasError, children, value } = props;
  let [isValid, setIsValid] = useState<boolean>(false);
  
  /**
   * Handles changes to the input value.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The change event.
   * @returns {void}
   */
  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (event.target.checked) {
      setHasError(false);
      setIsValid(true);
    } else {
      setHasError(true);
      setIsValid(false);
    }
    if (onChange) {
      const updatedEvent = {
        target: {
          name,
          value: '' + event.target.checked,
        },
      } as ChangeEvent<HTMLInputElement>;
      onChange(updatedEvent);
    }
  };
  return (

    <div className={divClass}>
      <div className='iagree_radio'>
      <input
        type="checkbox"
        id={id}
        name={name}
        onChange={handleChange}
        className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className}
        required={required}
        checked={(value === 'true') ? true : false}
      />
      <label htmlFor={id}>
        {children}
      </label>
      {hasError && <p className='error'>{errorMessage}</p>}
    </div>
    </div> 
  );
};

export default AgreeCheckBox;