import React, { ChangeEvent, ReactNode, MouseEvent } from 'react';
interface InputProps {
  name: string;
  id?: string;
  onChange?: (event: ChangeEvent<HTMLInputElement>) => void;
  divClass?: string;
  value: string;
  children: ReactNode;
  checked: boolean;
}
function Radio(props: InputProps) {
  const { name, id = 'id-' + props.name + '-' + props.value, onChange, divClass = 'form-group', children, value, checked } = props;
  const handleClick = (event: MouseEvent<HTMLInputElement> | ChangeEvent<HTMLInputElement>) => {
    if (onChange) {
      const set = {
        target: {
          name,
          value,
        },
      } as ChangeEvent<HTMLInputElement>;
      onChange(set);
    }
  };

  return (
    <div className={divClass}>
      <input
        type="radio"
        id={id}
        name={name}
        onChange={handleClick}
        onClick={handleClick}
        checked={checked}
        value={value}
      />
      <label htmlFor={id}>
        {children}
      </label>
    </div>
  );
};

export default Radio;