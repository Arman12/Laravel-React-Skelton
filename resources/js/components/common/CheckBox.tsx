import React, { ChangeEvent, ReactNode } from 'react';
interface InputProps {
  name: string;
  id?: string;
  onChange: (event: ChangeEvent<HTMLInputElement>) => void;
  divClass?: string;
  value: string;
  children: ReactNode;
  checked: boolean;
}

/**
 * CheckBox.
 *
 * 
 * @param {Object} props - Component props.
 * @param {string} props.name - Name attribute for the input element.
 * @param {string} props.value - value for the input element.
 * @param {string} [props.id] - dynamiclly add id to the element.
 * @param {ChangeEvent<HTMLInputElement>} [props.onChange] - callback.
 * @param {string} [props.divClass] - dynamiclly add divClass to the parnt element.
 * @param {ReactNode} [props.children] 
 * @param {boolean} [props.cheked] 
 * 
 * @returns {JSX.Element} - JSX representation of the component.
 */

function CheckBox(props: InputProps) {
  const { name, id = 'id-' + props.name, onChange, divClass = 'form-group', children, value, checked } = props;
  
  /**
   * Handles changes to the input value.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The onchange event.
   * @returns {void}
   */
  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (onChange) {
      onChange(event);
    }
  };

  return (
    <div className={divClass}>
      <input
        type="checkbox"
        id={id}
        name={name}
        onChange={handleChange}
        checked={checked}
        value={value}
      />
      <label htmlFor={id}>
        {children}
      </label>
    </div>
  );
};

export default CheckBox;