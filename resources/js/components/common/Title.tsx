import React, { ChangeEvent, useState } from 'react';
interface TitleDropdownProps {
  name: string;
  id?: string;
  className?: string;
  value?: string;
  label?: string;
  placeholder?: string;
  required?: boolean;
  onChange: (event: ChangeEvent<HTMLSelectElement>) => void;
  divClass?: string;
  hasError?: boolean;
  errorMessage?: string;
  setHasError: (isValid: boolean) => void;
}


/**
 * Title.
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
function Title(props: TitleDropdownProps) {
  const titles = ['Mr', 'Mrs', 'Miss', 'Dr', 'Prof', 'Rev'];
  let [isValid, setIsValid] = useState<boolean>(false);
  const { name, id = 'id-' + props.name, className = 'input-field', value, placeholder = 'Select title', required, onChange, label, divClass = 'form-group', hasError, errorMessage, setHasError } = props;
  
  /**
   * Handles changes to the input value.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The onchange event.
   * @returns {void}
   */
  const handleChange = (event: ChangeEvent<HTMLSelectElement>) => {
    if (onChange) {
      onChange(event);
      const value = event.target.value;
      if (value) {
        setHasError(false);
        setIsValid(true);
      } else if (required) {
        setHasError(true);
        setIsValid(false);
      }
    }
  };
  return (
    <div className={divClass}>
      {label && <label htmlFor={id}>{label} {required && <span>*</span>}</label>}
      <select id={id} value={value} onChange={handleChange} className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className} name={name} required >
        <option value="" hidden disabled>{placeholder}</option>
        {titles.map((title) => (
          <option key={title} value={title}>
            {title}
          </option>
        ))}
      </select>
      {hasError && <p className='error'>{errorMessage}</p>}
    </div>
  );
};

export default Title;