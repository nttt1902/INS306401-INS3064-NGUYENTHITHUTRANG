<?php
// src/Person.php

class Person {
    private int $age = 0;

    // Type Hinting: Tham số $age phải là số nguyên (int)
    public function setAge(int $age): void {
        if ($age < 0) {
            throw new InvalidArgumentException('Tuổi không được nhỏ hơn 0');
        }
        $this->age = $age;
    }

    // Type Hinting: Kết quả trả về phải là số nguyên (int)
    public function getAge(): int {
        return $this->age;
    }

    // Kiểm tra xem có phải người lớn không
    public function isAdult(): bool {
        return $this->age >= 18;
    }
}